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
}