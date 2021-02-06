<?php
namespace App\Repositories;


/**
* @category Sistemas integrales de pasarelas de pago.
* @since Creado: 2021-02-02
* @author Ing. Alfonso Chávez Baquero <alfonso.chb@gmail.com>
* @link Visita mi sitio: https://alfonsochb.com/
* @see Este archivo es parte de una prueba de integración de una tienda virtual con la
* pasarela de pagos PlacetoPay de Evertec.
* Es requerido datos de configuracion de las archivos:
* \tienda-placetopay\config\app_payments.php 
* \tienda-placetopay\.env
*/
class PlacetoPay
{

	# Variable en la cual se inicializa la conexión de webCheckout
    private $conection = [];


    # URL desde donde se sirve la API.
    private $url_api;


    /**
     * @method __construct - Método constructor de la clase.
     * @author Alfonso Chávez <alfonso.chb@gmail.com>
     * @return void - Retorna la inicialización del objeto.
    */
    public function __construct()
    {
    	$seed = date('c');
		if (function_exists('random_bytes')) {
		    $nonce = bin2hex(random_bytes(16));
		} elseif (function_exists('openssl_random_pseudo_bytes')) {
		    $nonce = bin2hex(openssl_random_pseudo_bytes(16));
		} else {
		    $nonce = mt_rand();
		}
		$tran_key = base64_encode(sha1($nonce . $seed . config('app_payments.tran_key'), true));
        $this->conection = [
			'login' => config('app_payments.login'),
			'seed' => $seed,
			'nonce' => base64_encode($nonce),
			'tranKey' => $tran_key
		];
		$this->url_api = "https://test.placetopay.com/redirection/api";
    }


    /**
     * @method webCheckout - Realiza la verificación de autenticación con Placetopay.
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  (array) $params - Variables de la orden de compra y pasarela de pago.
     * @return (array) - Estructura PHP.
     */
	public function webCheckout( $params=null )
	{
        try{
	    	if ( !$params or empty($params) ) return false;

	    	$data = !is_object($params) ? (object)$params : $params;

			$request = [
				'auth' => $this->conection,
				'buyer' => [
					'name' => $data->names,
					'surname' => $data->surnames,
					'email' => $data->email,
					'document' => $data->document,
					'documentType' => $data->document_type,
					'mobile' => $data->phone
				],
			    'payment' => [
			        'reference' => $data->reference,
			        'description' => $data->product_name,
			        'amount' => [
			            'currency' => 'COP',
			            'total' => $data->cost
			        ]
			    ],
			    'expiration' => date('c', strtotime('+2 days')),
			    'returnUrl' => route('orders.response', $data->reference),
			    'ipAddress' => '127.0.0.1',
			    'userAgent' => 'PlacetoPay Sandbox'
			];
			$response = $this->getJson( $this->url_api."/session", $request ); 
            return $response;
        }catch(\Exception $e) {
        	# Guardar en un log transaccional ($e->getCode(), $e->getMessage()).
        	return "No se ha logrado el checkout, ".$e->getLine()." ".$e->getMessage();
        }
	}


    /**
     * @method requestInformation - Obtener información de una órden de compra.
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  (int) $request_id - Id de la pasarela para identificar la órden en la pasarela.
     * @return (array) - Estructura PHP.
     */
	public function requestInformation( $request_id=null )
	{
        try{
			$request = [
				'auth' => $this->conection,
			];
			return $this->getJson( $this->url_api."/session/$request_id", $request );
        }catch(\Exception $e) {
        	# Guardar en un log transaccional ($e->getCode(), $e->getMessage()).
        	return "No se ha logrado obtener información, ".$e->getLine()." ".$e->getMessage();
        }
	}


    /**
     * @method reversePayment - Reversar una orden de compra en estado aprovada.
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  (strin) $reference - Referencia interna que la pasarela retorna en requestInformation.
     * @return (array) - Estructura con información de estado reversar.
     */
	public function reversePayment( $reference=null )
	{
        try{
			$request = [
				'auth' => $this->conection,
				'internalReference' => $reference
			];
			$response = $this->getJson( $this->url_api."/reverse", $request );
			return $response;
        }catch(\Exception $e) {
        	# Guardar en un log transaccional ($e->getCode(), $e->getMessage()).
        	return "No se ha logrado reversar transacción, ".$e->getLine()." ".$e->getMessage();
        }
	}


   /**
     * @method getJson - Método para obtener datos desde las API Restfull.
     * @author Alfonso Chávez <alfonso.chb@gmail.com>
     * @param (string) $url - Indica la URL desde donde se consumirá el recurso.
     * @param (array) $request - Los parametros requeridos por el API Restfull.
     * @return (array structure) - Estructura de datos limpios.
     */
    public function getJson( $url=null, $request='' )
    {
		$curl = curl_init( trim($url) );
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		return json_decode($json_response);
    }


}