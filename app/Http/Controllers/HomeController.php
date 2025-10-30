<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller{
    public function index(){
       
        $carrossel = DB::table('produtos')->select('*')->get();

        return view('home.index', compact('carrossel'));
    }
}