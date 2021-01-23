<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;



/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', [ProductsController::class, 'index'])->name('inicio');
Route::get('/productos', [ProductsController::class, 'index'])->name('productos.index');
Route::get('/productos/{code}', [ProductsController::class, 'show'])->name('products.show');

