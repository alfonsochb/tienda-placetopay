<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tienda PlacetoPay</title>

        <link href="{{url('img/favicon.png')}}" type="image/png" rel="icon">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

        <link href="{{ url('css/appstyles.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

                <div class="grid grid-cols-3 gap-4">
                    <div class="p-6">
                        <a href="{{ url('/') }}" class="mt-5">
                            <img class="w-64" src="{{url('img/logo.png')}}" alt="Placetopay">
                        </a>
                    </div>
                    <div class="col-span-2">
                        <div class="p-6">
                            <div class="bg-gray-50">
                                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:py-4 lg:px-8 lg:flex lg:items-center lg:justify-between">
                                    <h2 class="text-2xl font-extrabold tracking-tight text-gray-500 sm:text-2xl">
                                        <span class="block">Esta página es un proyecto de ejemplo funcional de tienda virtual con Laravel 8 y PlacetoPay</span>
                                    </h2>
                                </div>
                            </div>                        
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="grid grid-cols-1">
                        <div class="p-6 bg-white">
                            <table style="width: 100%" border="1">
                                <tr>
                                    <td>
                                        <img src="{{ url('img/iconos/'.$product->picture) }}" class="w-32" alt="{{ $order->product_name }}"/>
                                    </td>
                                    <td>
                                        <div class="text-xl font-medium text-black">
                                            <b>{{ $order->product_name }}</b>
                                        </div>
                                        <div class="text-xl font-medium text-black">
                                            <b style="color: blue; font-size: 1.2em;">
                                            	{{ 'COP $'.number_format( $order->product_cost, 0 ) }}
                                            </b>
                                        </div>
                                        <p class="text-gray-500">{{ $product->description }}</p>    
                                    </td>
                                    <td>

                                    	<?php if ( $order->status=='APPROVED' ): ?>
											<div class="text-center p-6 shadow sm:rounded-lg" style="background: #21C0AC; color: white;">
												<div class="mt-5 p-5">
													<h2 class="text-center">ORDEN DE COMPRA APROVADA</h2>
													<img class="w-64" src="{{ url('img/felicidades.png') }}"/>
													<h4>{{ 'Ya puedes disfrutar de tu curso!' }}</h4>
												</div>
											</div>
                                    	<?php endif; ?>

                                        <?php if ( $order->status=='PENDING' ): ?>
                                            <div class="text-center p-6 shadow sm:rounded-lg" style="background: #B23CFD; color: white;">
                                                <div class="mt-5 p-5">
                                                    <h2 class="text-center">ORDEN DE COMPRA PENDIENTE</h2>
                                                    <a href="{{ $order->process_url }}" class="btn btn-primary m-3">Pendiente</a>&nbsp;
                                                    <a href="{{ route('orders.repayment', $order->reference) }}" class="btn btn-primary m-3">Reintentar pago</a>
                                                    <br>
                                                    <a href="{{ route('home') }}" class="mb-3">Cancelar operación</a>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    	<?php if ( $order->status=='REJECTED' ): ?>
											<div class="text-center p-6 shadow sm:rounded-lg" style="background: #FFA900; color: white;">
												<div class="mt-5 p-5">
													<h2 class="text-center">ORDEN DE COMPRA RECHAZADA</h2>
													<a href="{{ route('orders.repayment', $order->reference) }}" class="btn btn-primary m-3">Reintentar pago</a>
                                                    <h4>{{ 'Por favor intente nuevamente.' }}</h4>
												</div>
											</div>
                                    	<?php endif; ?>                                        	                                 
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <br><br>
            </div>
        </div>
        <script type="text/javascript" src="{{ url('js/jquery-3.5.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('js/sweetalert.min.js') }}"></script>
        @if(Session::has('mensaje'))
            <script type="text/javascript">
                swal({
                    title: "Mensaje!",
                    text: "{!! Session::get('mensaje') !!}",
                    icon: "warning",
                    button: "OK",
                    dangerMode: true,
                    closeOnClickOutside: false
                });
            </script>
        @endif
    </body>
</html>



