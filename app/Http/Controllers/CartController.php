<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:produtos,id',
            'color'      => 'required|string',
            'size'       => 'required|string',
            'quantity'   => 'required|integer|min:1',
            'mode'       => 'nullable|string',
        ]);

        $produto = DB::table('produtos')->where('id', $data['product_id'])->first();

        if (!$produto) {
            return back()->with([
                'cart_error' => 'Produto não encontrado.',
                'cart_open'  => true,
            ]);
        }

        $detalhes = json_decode($produto->detalhes ?? '[]', true);
        $detalhes = is_array($detalhes) ? $detalhes : [];

        $variacaoSelecionada = null;

        foreach ($detalhes as $detalhe) {
            if (!is_array($detalhe)) {
                continue;
            }

            $cor = mb_strtolower(trim($detalhe['cor'] ?? ''));
            $tamanho = trim($detalhe['tamanho'] ?? '');

            if ($cor === mb_strtolower(trim($data['color'])) && $tamanho === trim($data['size'])) {
                $variacaoSelecionada = $detalhe;
                break;
            }
        }

        if (!$variacaoSelecionada) {
            return back()->with([
                'cart_error' => 'Variação do produto não encontrada.',
                'cart_open'  => true,
            ]);
        }

        $estoqueDisponivel = (int) ($variacaoSelecionada['qtd'] ?? 0);
        $quantity = (int) $data['quantity'];

        if ($estoqueDisponivel <= 0) {
            return back()->with([
                'cart_error' => 'Essa variação está sem estoque.',
                'cart_open'  => true,
            ]);
        }

        if ($quantity > $estoqueDisponivel) {
            return back()->with([
                'cart_error' => 'Quantidade maior que o estoque disponível.',
                'cart_open'  => true,
            ]);
        }

        $unitPrice = (float) ($variacaoSelecionada['valor'] ?? 0);
        $totalValue = $unitPrice * $quantity;

        $imagemPrincipal = null;
        if (!empty($variacaoSelecionada['imagens']) && is_array($variacaoSelecionada['imagens'])) {
            $imagemPrincipal = $variacaoSelecionada['imagens']['imagem1']
                ?? $variacaoSelecionada['imagens']['imagem2']
                ?? $variacaoSelecionada['imagens']['imagem3']
                ?? $variacaoSelecionada['imagens']['imagem4']
                ?? null;
        }

        $cart = session()->get('cart', []);

        $key = $produto->id . '|' . mb_strtolower(trim($data['color'])) . '|' . trim($data['size']);

        if (isset($cart[$key])) {
            $novaQuantidade = $cart[$key]['quantity'] + $quantity;

            if ($novaQuantidade > $estoqueDisponivel) {
                return back()->with([
                    'cart_error' => 'Você atingiu o limite de estoque dessa variação.',
                    'cart_open'  => true,
                ]);
            }

            $cart[$key]['quantity'] = $novaQuantidade;
            $cart[$key]['total_value'] = $cart[$key]['quantity'] * $cart[$key]['unit_price'];
        } else {
            $cart[$key] = [
                'product_id'   => $produto->id,
                'category'     => $produto->categorias,
                'title'        => $produto->titulo,
                'description'  => $produto->descricao,
                'size'         => trim($data['size']),
                'color'        => trim($data['color']),
                'quantity'     => $quantity,
                'stock'        => $estoqueDisponivel,
                'unit_price'   => $unitPrice,
                'total_value'  => $totalValue,
                'image'        => $imagemPrincipal,
                'details_item' => $variacaoSelecionada,
            ];
        }

        session()->put('cart', $cart);

        if (($data['mode'] ?? '') === 'buy_now') {
            return view('product.resumo', [
                'items' => [$cart[$key]],
            ]);
        }

        return back()->with([
            'cart_success' => 'Produto adicionado ao carrinho!',
            'cart_open'    => true,
        ]);
    }

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

    public function updateItem(Request $request)
    {
        $data = $request->validate([
            'key'      => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$data['key']])) {
            return back()->with('cart_open', true);
        }

        $item = $cart[$data['key']];
        $stock = (int) ($item['stock'] ?? 0);
        $newQuantity = (int) $data['quantity'];

        if ($stock > 0 && $newQuantity > $stock) {
            $newQuantity = $stock;
        }

        if ($newQuantity < 1) {
            $newQuantity = 1;
        }

        $cart[$data['key']]['quantity'] = $newQuantity;
        $cart[$data['key']]['total_value'] = $newQuantity * (float) $cart[$data['key']]['unit_price'];

        session()->put('cart', $cart);

        return back()->with('cart_open', true);
    }
}