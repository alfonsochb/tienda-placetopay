<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\WebCheckOutController;
use App\Http\Controllers\OrdersController;

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::get('/', [ProductsController::class, 'index'])->name('inicio');
Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::get('/products/{code}', [ProductsController::class, 'show'])->name('products.show');
Route::post('clients', [ClientsController::class, 'store'])->name('clients.store');
Route::get('/orders/response/{code}', [OrdersController::class, 'placetoPayResponse'])->name('orders.response');
