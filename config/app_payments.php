<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Login
    |--------------------------------------------------------------------------
    | Esta es la variable de login para conectar la pasarela de pago.
    |
    */
    'login' => env('PLACETOPAY_LOGIN', null),


    /*
    |--------------------------------------------------------------------------
    | Application TranKey
    |--------------------------------------------------------------------------
    | Esta es la variable de password para conectar la pasarela de pago.
    |
    */
    'tran_key' => env('PLACETOPAY_TRANKEY', null),


    /*
    |--------------------------------------------------------------------------
    | Application Url Request
    |--------------------------------------------------------------------------
    | Esta es la variable de consumo de los servicios para conectar la pasarela de pago.
    |
    */
    'url_request' => env('PLACETOPAY_URL_REQUEST', null),

];
