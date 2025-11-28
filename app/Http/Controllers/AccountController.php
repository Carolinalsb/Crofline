<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller{
    public function register(){
       
        return redirect()->back()->with('success', 'Registro realizado com sucesso!');
    }
}