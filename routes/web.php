<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AccountController;

//Rota Home
Route::get('/', [HomeController::class, 'index'])->name('home.index');

//Rota Product
Route::post('/product/produtos', [ProductController::class, 'produtos'])->name('product.produtos');
Route::get('/product/show/{id}', [ProductController::class, 'show'])->name('product.show');


//Rota Account Controller
Route::post('/account/register', [AccountController::class, 'register'])->name('account.register');

//Cart Controller
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');