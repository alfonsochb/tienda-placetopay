<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PlacetoPay;
use App\Models\Product;
use App\Models\Client;
use App\Models\Order;


/**
* @category Sistema integral de pasarela de pago WebCheckOut Placetopay.
* @since Creado: 2021-02-02
* @author Ing. Alfonso Chávez Baquero <alfonso.chb@gmail.com>
*/
class OrdersController extends Controller
{


    /**
     * @method rePayment - Reintentar pagar una orden rechazada o declinada.
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param String $ref - Referencia que identifica a la órden de compra.
     * @return redirect registerOrder()
     */
    public function rePayment( $ref='' )
    {
        $order = Order::where( 'reference', $ref )->first();
        return $this->registerOrder( [
            'product_id' => $order->product_id,
            'client_id' => $order->client_id,
        ]);
    }


    /**
     * @method registerOrder - Registrar órden de compra.
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  (array) $params - Datos requeridos por la pasarela de pago.
     * @return redirect - Redirecciona a la pasarela de pagos.
     */
    public function registerOrder( $params=array() )
    {
        try{
            if( !isset($params['product_id']) or !is_numeric($params['product_id']) ) {
                throw new \Exception('No se tiene identificador del producto', 000 );
            }

            if( !isset($params['client_id']) or !is_numeric($params['client_id']) ) {
                throw new \Exception('No se tiene identificador del cliente', 000 );
            }

            $product = Product::where('id', $params['product_id'])->first();
            $client = Client::where('id', $params['client_id'])->first();
            #$payment = new \stdClass;
            $payment = (object)[
                'product_id'    => $product->id,
                'client_id'     => $client->id,
                'product_name'  => str_replace('-', ' ', $product->product_name),
                'description'   => $product->description,
                'cost'          => $product->cost,
                'picture'       => $product->picture,
                'names'         => $client->names,
                'surnames'      => $client->surnames,
                'email'         => $client->email,
                'phone'         => $client->phone
            ];
            unset( $product, $client );
            if ( !isset($payment->product_id) or !isset($payment->client_id) ) {
                throw new \Exception('No existen parámetros para conectar con la pasarela de pagos.', 000 );
            }
             
            $order = Order::where('client_id', $payment->client_id)
                ->where('product_id', $payment->product_id)
                ->whereIn('status', ['OK', 'PENDING','REJECTED'])
                ->orderBy('id', 'desc')
                ->first();
                //->toSql();

            if ( isset($order->id) and is_numeric($order->id) and $order->id>0 ) {
                // Si la órden de compra existe, entonces actualizarla.
                $order->customer_name = $payment->names.' '.$payment->surnames;
                $order->customer_email = $payment->email;
                $order->customer_mobile = $payment->phone;
                $order->product_name = $payment->product_name;
                $order->product_cost = $payment->cost;
                $order->save();
            }else{
                // Si la órden de compra no existe, entonces crearla.
                $order_id = Order::insertGetId([
                    'client_id' => $payment->client_id,
                    'product_id' => $payment->product_id,
                    'customer_name' => $payment->names.' '.$payment->surnames,
                    'customer_email' => $payment->email,
                    'customer_mobile' => $payment->phone,
                    'product_name' => $payment->product_name,
                    'product_cost' => $payment->cost,
                    'status' => 'OK',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
                $order = Order::findOrFail( $order_id );
                $order->reference = "CL".$payment->client_id."PR".$payment->product_id."OR".$order_id;
                $order->save();
            }
            $payment->reference = $order->reference;
            $placeto_pay = new PlacetoPay();
            $result = $placeto_pay->webCheckout( $payment );

            // Obtener la URL de pagos.
            if ( !isset($result->status->status) ) {
                throw new \Exception('No se ha establecido conección con la pasarela de pagos.', 000 );
            }

            // Hay una URL de procesamiento, continuar en la pasarela de pago.
            if ( isset($result->requestId) and isset($result->processUrl) ) {
                $order->request_id = $result->requestId;
                $order->pass_message = $result->status->message;
                $order->process_url = $result->processUrl;
                $order->status = $result->status->status;
                $order->save();
                $redirect = @trim($result->processUrl); 
                header('Location: ' . $redirect);
                exit;
            }
        }catch(\Exception $e) {
            /**
             * @todo - Se debe guardar en un log ($e->getCode(), $e->getMessage()).
             */
            $message = $e->getLine()." ".$e->getMessage();
            return redirect('products')->with('mensaje', $message);
        }
        return redirect('products')->with('mensaje', 'Fallo inesperado, por favor intente más tarde.');
    }


    /**
     * @method webCheckout - Obtener respuestas desde pasarela.
     * @author Alfonso Chávez <alfonso.chb@gmail.com>
     * @param (string) $reference - Referencia con la que se consulta la órden de compra.
     * @return view - Vista con las opciones requeridas según la respuesta de la pasarela PlacetoPay.
     */
    public function placetoPayResponse( $reference='' )
    {
        try{
            $order = Order::where( 'reference', strtoupper($reference) )
                ->orderBy('id', 'desc')
                ->first();
                //->toSql();   	

	    	if ( !isset($order) or empty($order) ) {
	    		throw new \Exception("No se tiene identificación de la órden $reference.", 000 );
	    	}
			
            $placeto_pay = new PlacetoPay();
			$info = $placeto_pay->requestInformation( $order->request_id );
        	if ( !isset($info->status->status) ) {
        		throw new \Exception('Se ha perdido la conección con la pasarela de pagos.', 000 );
        	}

            $order->pass_message = $info->status->message;
            $order->status = $info->status->status;
            $order->save();

            return view('orders.response_pay', [
                'product' => Product::where('id', $order->product_id )->first(),
                'order' => $order,
                //'payment' => $info
            ]);

        }catch(\Exception $e) {
            /**
             * @todo - Se debe guardar en un log ($e->getCode(), $e->getMessage()).
             */
            return redirect('products')->with('mensaje', $e->getMessage());
        }
        return redirect('products')->with('mensaje', 'Fallo inesperado, por favor intente más tarde.');
    }


}
