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
                                <div class="text-right">
                                    <a href="javascript:status()" class="p-3 text-blue-600">
                                        Consultar 
                                    </a><br>
                                </div>
                                
                            </div>                        
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <?php foreach ($products as $key => $info): ?>
                            <div class="py-6">
                                <div class="p-2 max-w-sm mx-auto bg-white rounded-xl shadow-md flex items-center space-x-2">
                                    <div class="flex-shrink-0">
                                        <img class="h-20 w-20" src="{{url('img/iconos/'.$info->picture)}}" alt="{{ $info->description  }}">
                                    </div>
                                    <div class="px-6">
                                        <div class="text-xl font-medium text-black">
                                            <b>{{ $info->product_name }}</b>
                                        </div>
                                        <div class="text-xl font-medium text-black">
                                            <b style="color: blue; font-size: 1.2em;">{{ 'COP $'.number_format( $info->cost, 0 ) }}</b>
                                        </div>
                                        <p class="text-gray-500">{{ $info->description  }}</p>
                                        <div class="flex items-center">
                                            <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="-mt-px w-5 h-5 text-gray-600">
                                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <a href="{{ route('products.show', $info->id) }}" class="mt-auto bg-violet-800 bg-opacity-50 hover:bg-opacity-75 transition-colors duration-200 rounded-xl font-semibold py-2 px-4 inline-flex">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit; color: green;">Comprar este curso</font>
                                                </font>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <br>

                <div class="flex justify-center sm:text-right">
                    {{ $products->links() }}
                </div>                
                <hr><br><br>

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
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                });
            </script>
        @endif

        <script type="text/javascript">
            function status(){
                swal({
                    content: {
                        element: "input",
                        attributes: {
                            placeholder: "Ingrese la referencia",
                            type: "text",
                            required: "required"
                        },
                    },
                    button: "Consultar",
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                }).then((value) => {
                    //console.log(value.length);
                    if (value.length>3) {
                        document.location.href = "{{ url('orders/response') }}/"+value;
                    }
                    return false;
                });
            }
        </script>
    </body>
</html>
