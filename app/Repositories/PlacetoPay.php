<?php
namespace App\Repositories;


/**
* @category Sistema integral de pasarela de pago WebCheckOut Placetopay.
* @since Creado: 2021-02-02
* @author Ing. Alfonso Chávez Baquero <alfonso.chb@gmail.com>
* @see Este archivo es parte de una prueba de integración de tienda virtual con la
* pasarela de pago PlacetoPay de Evertec.
* Se requieren datos de configuración de archivo:
* \tienda-placetopay\config\app_payments.php
* \tienda-placetopay\.env
*/
class PlacetoPay
{

	# Variable en la que se inicializa la conexión con webCheckout.
    private $conection = [];


    # URL desde donde se sirve la API de webCheckout.
    private $url_api;


    /**
     * @method __construct - M{etodo constructor de la clase.
     * @author Alfonso Chávez <alfonso.chb@gmail.com>
     * @return (void) - Retorna la inicialización del objeto.
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
     * @param  (array) $params - Variables con detalle de la orden de compra y pasarela de pago.
     * @return (array) - PHP estructura.
     */
	public function webCheckout( $params=null )
	{
        try{
	    	if ( !$params or empty($params) ) return false;

	    	$data = is_object($params) ? $params : (object)$params;
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
        	/**
        	 * @todo - Se debe guardar en un log ($e->getCode(), $e->getMessage()).
        	 */
        	return "No se ha logrado el checkout, linea: ".$e->getLine()." ".$e->getMessage();
        }
	}


    /**
	 * @method requestInformation - Obtener información sobre una orden de compra.
     * @author Alfonso Chávez <alfonso.chb@gmail.com>
     * @param (int) $request_id - Id clave para identificar el pedido en la pasarela de pago.
     * @return (array) - PHP estructura.
     */
	public function requestInformation( $request_id=null )
	{
        try{
			$request = [ 'auth' => $this->conection ];
			return $this->getJson( $this->url_api."/session/$request_id", $request );
        }catch(\Exception $e) {
        	/**
        	 * @todo - Se debe guardar en un log ($e->getCode(), $e->getMessage()).
        	 */
        	return "No se ha logrado obtener información, ".$e->getLine()." ".$e->getMessage();
        }
	}


    /**
     * @method reversePayment - Revertir una orden de compra al estado aprobado.
     * @author Alfonso Chávez <alfonso.chb@gmail.com> 
     * @param  (strin) $reference - Referencia interna que devuelve la clave de enlace en requestInformation.
     * @return (array) - estructura con información del proceso de reversar.
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
        	/**
        	 * @todo - Se debe guardar en un log ($e->getCode(), $e->getMessage()).
        	 */
        	return "No se ha logrado reversar transacción, ".$e->getLine()." ".$e->getMessage();
        }
	}


   /**
     * @method getJson - Método para obtener información en formato JSON Restful APIs.
     * @author Alfonso Chávez <alfonso.chb@gmail.com>
     * @param (string) $url - Indica la URL desde donde se consumirá el recurso.
     * @param (array) $request - Los parámetros requeridos por el servicio Restfull API.
     * @return (array structure) - Estructura de datos formateado a PHP.
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