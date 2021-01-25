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


    public function __construct()
    {
    	$this->conection();
    	//...
    }


    /**
     * Construye el arreglo de conección con las variables de entorno.
     *
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @return struc | Estructura PHP.
     */
    public function conection()
    {
    	$seed = date('c');
		if (function_exists('random_bytes')) {
		    $nonce = bin2hex(random_bytes(16));
		} elseif (function_exists('openssl_random_pseudo_bytes')) {
		    $nonce = bin2hex(openssl_random_pseudo_bytes(16));
		} else {
		    $nonce = mt_rand();
		}
		$nonce_base64 = base64_encode($nonce);
		$tran_key = base64_encode(sha1($nonce . $seed . config('app_payments.tran_key'), true));
        $this->conection = [
			'login' => config('app_payments.login'),
			'seed' => $seed,
			'nonce' => $nonce_base64,
			'tranKey' => $tran_key
		];
    }


    /**
     * Realizar la petición a la API PlacetoPay.
     *
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  $request | Armado de datos requeridos por la API.
     * @param  $api_params | Identificador de una órden de compra.
     * @return struc | Estructura PHP.
     */
    public function getJson( $request='', $api_params=null )
    {
		$url = config('app_payments.url_request');
		if ( $api_params ) {
			$url.=$api_params;
		}
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		return json_decode($json_response);
    }


    /**
     * Recibir las variables de una orden de compra y realizar transacción a la pasarela de pagos.
     *
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
					'document' => '',
					'documentType' => '',
					'mobile' => $data->phone
				],
			    'payment' => [
			        'reference' => $data->order_ref,
			        'description' => $data->product_name,
			        'amount' => [
			            'currency' => 'COP',
			            'total' => $data->cost
			        ]
			    ],
			    'expiration' => date('c', strtotime('+2 days')),
			    'returnUrl' => route('orders.response', $data->order_ref),
			    'ipAddress' => '127.0.0.1',
			    'userAgent' => 'PlacetoPay Sandbox'
			];
			//echo "<pre>"; print_r($request); die;
            return $this->getJson( $request ); 
        }catch(\Exception $e) {
        	# Guardar en un log transaccional ($e->getCode(), $e->getMessage()).
        	//echo "<pre>Mensaje: ".$e->getLine().' - '.$e->getMessage(); die;
        	return false;
        }
	}
	

    /**
     * Obtener información de una órden de compra.
     *
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  $request_id | Referencia para identificar la órden.
     * @return struc | Estructura PHP.
     */
	public function getInfoOrder( $request_id=null )
	{
        try{
			$request = [
				'auth' => $this->conection,
			];
			return $this->getJson( $request, $request_id );
        }catch(\Exception $e) {
        	# Guardar en un log transaccional ($e->getCode(), $e->getMessage()).
        	//echo "<pre>Mensaje: ".$e->getLine().' - '.$e->getMessage(); die;
        	return false;
        }
	}



}