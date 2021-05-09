<?php

/**
* @category Sistema integral de pasarela de pago WebCheckOut Placetopay.
* @since Creado: 2021-02-02
* @author Ing. Alfonso Chávez Baquero <alfonso.chb@gmail.com>
*/


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\OrdersController;

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::get('/', [ProductsController::class, 'index'])->name('home');
Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::get('/products/{code}', [ProductsController::class, 'show'])->name('products.show');
Route::post('clients', [ClientsController::class, 'store'])->name('clients.store');
Route::get('/orders/response/{code}', [OrdersController::class, 'placetoPayResponse'])->name('orders.response');
Route::get('/orders/repayment/{ref}', [OrdersController::class, 'rePayment'])->name('orders.repayment');
