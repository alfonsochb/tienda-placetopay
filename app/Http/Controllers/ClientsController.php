<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Product;
use App\Models\Order;
use App\Repositories\PlacetoPay;

class ClientsController extends Controller
{
    
    protected $placetopay;

    public function __construct( PlacetoPay $placetopay )
    {
        // Integración pasarela de pagos por inyección de dependencias.
        $this->placetopay=$placetopay;
    }

    /**
     * Crear un nuevo cliente si NO existe en la base de datos y llamar 
     * al procesamiento de crear Nueva orden de compra.
     *
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  \Illuminate\Http\Request  $request
     * @return redirect
     */
    public function store( Request $request )
    {
        $client_id = 0;
        try{
	        if( !$request->isMethod('post') ) {
	        	throw new \Exception('Esta acción no esta permitida, por favor verificar el método para de envio de datos.', 000 );
	        }

	        $data = $request->All();
	        $email = @trim($data['email']);
	        $client = Client::where('email', strtolower($email))->first();
	        if ( !isset($client) or empty($client) ) {
		        
		        # Aquí: Realizar validaciones de datos, se omite por cuestión de tiempo de la prueba.
		        // $request->validate([...]);

	           	$client_id = Client::insertGetId([
					'names' => utf8_encode( ucwords(strtolower($data['nombres'])) ),
					'surnames' => utf8_encode( ucwords(strtolower($data['apellidos'])) ),
					'email' => strtolower(trim($data['email'])),
					'phone' => @trim($data['celular']),
					'created_at' => date("Y-m-d H:i:s"),
					'updated_at' => date("Y-m-d H:i:s")
	            ]);
	        }

	        $client = Client::where('email', strtolower($email))->first();
	        if ( isset($client->id) and is_numeric($client->id) and $client->id>0 ) {
				$params_payment = [
					'product_id' 	=> $data['product_id'],
					'client_id' 	=> $client->id,
					'email' 		=> $client->email,
					'document' 		=> '',
					'document_type' => '',
		            'names' 		=> $client->names,
		            'surnames' 		=> $client->surnames,
		            'phone' 		=> $client->phone,
					'reference' 	=> 'REF-CL'.$client->id.'-PR'.$data['product_id'],

				];
	        }

	        if ( !empty($params_payment) ) {
	        	return $this->registerOrder( $params_payment );
	        }
        }catch(\Exception $e) {
        	# Guardar en un log transaccional ($e->getCode(), $e->getMessage()).
        	return redirect('products')->with('mensaje', $e->getLine().' '.$e->getMessage());
        }
        return redirect('products')->with('mensaje', 'Fallo inesperado, por favor intente más tarde.');
    }


    /**
     * Registrar la órden de compra.
     *
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  $params información de registro y datos requeridos en la pasarela.
     * @return redirect
     */
    public function registerOrder( $params=array() )
    {
        try{
	        if( !isset($params['product_id']) ) {
	        	throw new \Exception('No se tiene identificador del producto', 000 );
	        }

	        $product = Product::where('id', $params['product_id'])->first();
	        if( !isset($product->id) ) {
	        	throw new \Exception('No se tiene información del producto', 000 );
	        }

			$product = [
				'product_id' 	=> $product->id,
	            'product_name' 	=> str_replace('-', ' ', $product->product_name),
	            'description' 	=> $product->description,
	            'cost' 			=> $product->cost,
	            'picture' 		=> $product->picture,
			];
			$payment = (object)array_merge($params, $product);

			if ( empty($payment) ) {
				throw new \Exception('No existen parametros para conectar con la pasarela de pagos.', 000 );
	        }
	        	
        	$result = $this->placetopay->webCheckout( $payment );
        	if ( !isset($result->status->status) ) {
        		throw new \Exception('No se ha establecido conección con la pasarela de pagos.', 000 );
        	}

        	if ( isset($result->requestId) and isset($result->processUrl) ) {
		       	$order_id = Order::insertGetId([
					'client_id' => $payment->client_id,
					'product_id' => $payment->product_id,
					'customer_name' => $payment->names.' '.$payment->surnames,
					'customer_email' => $payment->email,
					'customer_mobile' => $payment->phone,
					'product_name' => $payment->product_name,
					'product_cost' => $payment->cost,
					'reference' => $payment->reference,
					'request_id' => $result->requestId,
					'pass_message' => $result->status->message,
					'process_url' => $result->processUrl,
					'status' => strtoupper($result->status->status),
					'created_at' => date("Y-m-d H:i:s"),
					'updated_at' => date("Y-m-d H:i:s")
		        ]);
		        $redirect = trim($result->processUrl);
		        header('Location: ' . $redirect);
		        exit;
        	}
        }catch(\Exception $e) {
        	# Guardar en un log transaccional ($e->getCode(), $e->getMessage()).
        	return redirect('products')->with('mensaje', $e->getLine().' '.$e->getMessage());
        }
        return redirect('products')->with('mensaje', 'Fallo inesperado, por favor intente más tarde.');
    }


}
