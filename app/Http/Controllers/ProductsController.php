<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    
    public $products;


    /**
     * Listar los productos de la tienda virtual.
     *
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @return view | Vista con todos los productos de la tienda virtual.
     */
    public function index()
    {
    	$this->products = Product::all();
    	return view('welcome', [
    		'listado' => $this->products
    	]);
    }


    /**
     * Detalle del producto y formulario de compra.
     *
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  $produc_id | Id identificador del producto.
     * @return view | Vista con detalles de producto y formulario para realizar una compra.
     */
    public function show( $produc_id=0 )
    {
    	return view('products.show', [
    		'product' => Product::findOrFail( $produc_id )
    	]);
    }

}
