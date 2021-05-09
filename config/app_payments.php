<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Application Login
    |--------------------------------------------------------------------------
    | This is the login variable to connect the payment gateway.
    |
    */
    'login' => env('PLACETOPAY_LOGIN', null),


    /*
    |--------------------------------------------------------------------------
    | Application TranKey
    |--------------------------------------------------------------------------
    | This is the password variable to connect the payment gateway.
    |
    */
    'tran_key' => env('PLACETOPAY_TRANKEY', null),


    /*
    |--------------------------------------------------------------------------
    | Application Url Request
    |--------------------------------------------------------------------------
    | This is the variable of consumption of the services to connect the payment gateway.
    |
    */
    'url_request' => env('PLACETOPAY_URL_REQUEST', null),

];
