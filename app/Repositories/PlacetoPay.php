<?php
namespace App\Repositories;

/*
 *
 * Este archivo es parte de una prueba de integración de una tienda virtual con la
 * pasarela de pagos PlacetoPay de Evertec.
 *
 * (c) Ing. Alfonso Chávez Baquero <alfonso.chb@gmail.com>
 * GitHub: https://github.com/alfonsochb
 * Site: https://www.alfonsochb.com
 * 
 * Es requerido datos de configuracion de las archivos:
 * \tienda-placetopay\config\app_payments.php 
 * \tienda-placetopay\.env
 *
 */

class PlacetoPay
{

    private $conection = [];


    private $url_api;


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
     * @method Recibir los parametros de una orden de compra y realizar transacción en Placetopay.
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  $params | Variables de la orden de compra y pasarela de pago.
     * @return struc | Estructura PHP.
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
     * @method Obtener información de una órden de compra.
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  $pasarela_id | id de la pasarela para identificar la órden.
     * @return struc | Estructura PHP.
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
     * @method Reversar una orden de compra en estado aprovada.
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  $reference | Ina referencia interna que la pasarela retorna en el paso anterior.
     * @return struc | Estructura PHP.
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
     * @method Realizar la petición a la API PlacetoPay por metodo POST.
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  $request | Armado de datos requeridos por la API.
     * @param  $api_params | Identificador de una órden de compra.
     * @return struc | Estructura PHP.
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