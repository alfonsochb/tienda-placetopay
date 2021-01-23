<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    public $products;

    public function index()
    {
    	$this->products = Product::all();
    	return view('welcome', [
    		'listado' => $this->products
    	]);
    }

    public function show( $produc_id=0 )
    {
    	return view('products.show', [
    		'product' => Product::findOrFail( $produc_id )
    	]);
    }

}
