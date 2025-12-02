<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller{
    public function produtos(Request $request){
        $dados = DB::table('produtos')
        ->select('*')
        ->where('categorias', $request->input('categoria'))
        ->get();

        return view('product.produtos', compact('dados'));
    }
    public function show($id){
        $produto = DB::table('produtos')
        ->select('*')
        ->where('id', $id)
        ->first();    
        return view('product.show', compact('produto'));
    }

    public function resumo(Request $request)
    {
        $selectedKeys = $request->input('selected_items', []);

        $cart = session()->get('cart', []);
        $itemsSelecionados = [];

        foreach ($selectedKeys as $key) {
            if (isset($cart[$key])) {
                $itemsSelecionados[$key] = $cart[$key];
            }
        }

        // Se não selecionou nada, volta pro carrinho
        if (empty($itemsSelecionados)) {
            return back()->with([
                'cart_error' => 'Selecione ao menos um produto para continuar.',
                'cart_open'  => true,
            ]);
        }

        return view('product.resumo', [
            'items' => $itemsSelecionados,
        ]);
    }
}
