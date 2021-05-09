<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;


/**
* @category Sistema integral de pasarela de pago WebCheckOut Placetopay.
* @since Creado: 2021-02-02
* @author Ing. Alfonso Chávez Baquero <alfonso.chb@gmail.com>
*/
class ProductsController extends Controller
{
    

    /**
     * @method index - Listar productos
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @return view - Vista que presenta el listado de los productos de la tienda virtual.
     */
    public function index()
    {
        return view('welcome', [
    		'products' => Product::latest()->paginate(10)
    	]);
    }


    /**
     * @method show - Visualizar detalle.
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param (int) $produc_id - Id identificador del producto.
     * @return view - Retorna la vista en detalle del producto y formulario de compra..
     */
    public function show( $produc_id=0 )
    {
        if ( empty($produc_id) or !is_numeric($produc_id) ) {
            return redirect('products')->with('mensaje', 'El producto que intenta comprar no existe o no esta disponible.');
        }

    	return view('products.show', [
    		'product' => Product::findOrFail( $produc_id )
    	]);
    }


}
