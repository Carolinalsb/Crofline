<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PagamentoController;

//Rota Home
Route::get('/', [HomeController::class, 'index'])->name('home.index');

//Rota Product
Route::post('/product/produtos', [ProductController::class, 'produtos'])->name('product.produtos');
Route::get('/product/show/{id}', [ProductController::class, 'show'])->name('product.show');
Route::post('/product/resumo', [ProductController::class, 'resumo'])->name('product.resumo');

//Rota Account Controller
Route::post('/account/register', [AccountController::class, 'register'])->name('account.register');
Route::post('/login', [AccountController::class, 'login'])->name('account.login');
Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');

//Cart Controller
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'removeItem'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'updateItem'])->name('cart.update');

//Pagamento Controller
Route::post('/pagamento/checkout', [PagamentoController::class, 'checkout'])->name('pagamento.checkout');
Route::post('/pagamento/pix', [PagamentoController::class, 'processarPix'])->name('pagamento.pix');
Route::post('/pagamento/cartao', [PagamentoController::class, 'processarCartao'])->name('pagamento.cartao');

Route::match(['get', 'post'], '/pagamento/retorno', [PagamentoController::class, 'checkoutResp'])
    ->name('pagamento.checkoutResp');

Route::get('/minhas-compras', [PagamentoController::class, 'minhasCompras'])
    ->name('pagamento.minhasCompras');