<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Validação básica do que vem da página show
        $data = $request->validate([
            'product_id' => 'required|integer|exists:produtos,id',
            'color'      => 'required|string',
            'quantity'   => 'required|integer|min:1',
        ]);

        // Busca o produto no banco
        $produto = DB::table('produtos')->where('id', $data['product_id'])->first();

        if (!$produto) {
            return back()->with('cart_error', 'Produto não encontrado.');
        }

        $quantity   = (int) $data['quantity'];
        $unitPrice  = (float) $produto->valor;
        $totalValue = $unitPrice * $quantity;

        // Recupera carrinho atual da sessão (ou cria vazio)
        $cart = session()->get('cart', []);

        // Chave única por produto + cor (se quiser incluir tamanho, só concatenar também)
        $key = $produto->id . '|' . mb_strtolower($data['color']);

        if (isset($cart[$key])) {
            // Já existe esse produto + cor no carrinho: soma quantidade e recalcula total
            $cart[$key]['quantity']    += $quantity;
            $cart[$key]['total_value']  = $cart[$key]['quantity'] * $cart[$key]['unit_price'];
        } else {
            // Novo item no carrinho
            $cart[$key] = [
                'product_id'  => $produto->id,
                'title'       => $produto->titulo,
                'size'        => $produto->tamanho,
                'color'       => $data['color'],
                'quantity'    => $quantity,
                'unit_price'  => $unitPrice,
                'total_value' => $totalValue,
                'image'       => $produto->imagem, // nome do arquivo da imagem
            ];
        }

        // Salva carrinho de volta na sessão
        session()->put('cart', $cart);

        // Volta pra mesma página, com mensagem e mandando abrir o popup
        return back()->with([
            'cart_success' => 'Produto adicionado ao carrinho!',
            'cart_open'    => true,
        ]);
    }

    /**
     * Remove um item específico do carrinho (pela chave da sessão).
     */
    public function removeItem(Request $request)
    {
        $key = $request->input('key');

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return back()->with('cart_open', true);
    }

   
    
}
