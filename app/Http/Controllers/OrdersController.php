<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\PlacetoPay;

class OrdersController extends Controller
{
    

	protected $placetopay;


    public function __construct( PlacetoPay $placetopay )
    {
        // Integración pasarela de pagos por inyección de dependencias.
        $this->placetopay = $placetopay;
    }


    /**
     * Obtener respuesta de la pasarela.
     *
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  $response | Referencia enviada a la pasarela para que nos retorne identificados de registro.
     * @return view | Vista con las acciones requeridas de acuerdo a la respuesta de la pasarela PlacetoPay.
     */
    public function placetoPayResponse( $response='' )
    {
        try{
	    	
	    	$order = Order::where('reference', strtoupper($response))->first();
	    	if ( !isset($order) or empty($order) ) {
	    		throw new \Exception('No se tiene identificación de la órden.', 000 );
	    	}
			
			$payment = $this->placetopay->requestInformation( $order->request_id );
        	if ( !isset($payment->status->status) ) {
        		throw new \Exception('Se ha perdido la conección con la pasarela de pagos.', 000 );
        	}
        	Order::whereId($order->id)->update([
        		'pass_message' => $payment->status->message,
        		'status' => strtoupper($payment->status->status)
        	]);
			return view('orders.response_pay', [ 
				'order' => Order::where('reference', strtoupper($response))->first(),
				'payment' => $payment,
				'product' => Product::where('id', $order->product_id )->first()
			]);
        }catch(\Exception $e) {
            # Guardar en un log transaccional ($e->getCode(), $e->getMessage()).
            $message = $e->getLine()." ".$e->getMessage();
            return redirect('products')->with('mensaje', $message);
        }
        return redirect('products')->with('mensaje', 'Fallo inesperado, por favor intente más tarde.');
    }


}
