<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\OrdersController as Orders;
use App\Models\Client;


/**
* @category Sistema integral de pasarela de pago WebCheckOut Placetopay.
* @since Creado: 2021-02-02
* @author Ing. Alfonso Chávez Baquero <alfonso.chb@gmail.com>
*/
class ClientsController extends Controller
{
    
    /**
     * @method store - Registrar cliente.
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param \Illuminate\Http\Request $request
     * @return Redirect controller OrdersController
     * @see Crea un nuevo cliente si NO existe en la base de datos y llama al procesamiento 
     * de crear una nueva orden de compra pasando los datos de producto y cliente.
     */
    public function store( Request $request )
    {
        try{
	        if( !$request->isMethod('post') ) {
	        	throw new \Exception('Esta acción no esta permitida, por favor verificar el método para el envio de datos.', 000 );
	        }
			
			$data = $request->All();
            $validated = Validator::make( $data, Client::$rules, Client::$messages );
            if ( $validated->fails() ) {
                return redirect()->back()->withErrors( $validated )->withInput();
                #$errors = []; $list_errors = $validated ->errors(); foreach ($list_errors->all() as $message) { array_push($errors, $message ); }
            }
            $data = array_map("trim", $data);
			if( !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ){
				return redirect()->back()->with('mensaje', 'El email no esta en formato correcto.')->withInput();
			}

			$client = Client::where('email', strtolower($data['email']))->first();
			if ( isset($client->id) and is_numeric($client->id) and $client->id>0 ) {
				// Actualizar un cliente existente.
				$client->names = utf8_encode( ucwords( strtolower($data['names'] ) ) );
				$client->surnames = utf8_encode( ucwords( strtolower($data['surnames'] ) ) );
				$client->phone = @trim($data['phone']);
				$client->updated_at = date("Y-m-d H:i:s");
				$client->save();
			}else{
				// Registrar nuevo cliente.
	           	$id = Client::insertGetId([
					'names' 		=> utf8_encode( ucwords( strtolower($data['names'])) ),
					'surnames' 		=> utf8_encode( ucwords( strtolower($data['surnames']) ) ),
					'email' 		=> strtolower( trim($data['email']) ),
					'phone' 		=> @trim( $data['phone'] ),
					'created_at' 	=> date("Y-m-d H:i:s"),
					'updated_at' 	=> date("Y-m-d H:i:s")
	            ]);
	            $client = Client::findOrFail( $id );
			}

			$params_payment = [
				'product_id' => $data['product_id'],
				'client_id' => $client->id,
			];
			unset($data, $client);
			$orders = new Orders();
        	return $orders->registerOrder( $params_payment );
	        
        }catch(\Exception $e) {
        	/**
        	 * @todo - Se debe guardar en un log ($e->getCode(), $e->getMessage()).
        	 */
        	$message = "".$e->getLine()." ".$e->getMessage();
        	return redirect()->back()->with('mensaje', $message);
        }
        return redirect('products')->with('mensaje', 'Fallo inesperado, por favor intente más tarde.');
    }


}



