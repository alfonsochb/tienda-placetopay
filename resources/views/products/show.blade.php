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
                            <form action="{{ route('clients.store') }}" method="POST" autocomplete="off" >
                                @csrf
                                <input type="hidden" name="product_id" value="<?=$product->id?>" readonly="true">
                                <table style="width: 100%" border="1">
                                    <tr>
                                        <td rowspan="3">
                                            <img src="{{url('img/iconos/'.$product->picture)}}" class="w-32" alt="<?=$product->product_name?>"/>
                                        </td>
                                        <td rowspan="3">
                                            <div class="text-xl font-medium text-black">
                                                <b><?=$product->product_name?></b>
                                            </div>
                                            <div class="text-xl font-medium text-black">
                                                <b style="color: blue; font-size: 1.2em;">
                                                    <?='COP $'.number_format( $product->cost, 0 )?>
                                                </b>
                                            </div>
                                            <p class="text-gray-500"><?=$product->description?></p>    
                                        </td>
                                        <td>
                                            <div class="form-group px-2">
                                                <strong class="text-gray-600">Nombre:</strong>
                                                <input type="text" 
                                                    name="names" 
                                                    value="{{ old('names') }}" 
                                                    placeholder="Ingresa tu nombre" 
                                                    required="true" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group px-2">
                                                <strong class="text-gray-600">Apellido:</strong>
                                                <input type="text" 
                                                    name="surnames" 
                                                    value="{{ old('surnames') }}" 
                                                    placeholder="Ingresa tus apellidos" 
                                                    required="true" />
                                            </div>                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group px-2">
                                                <strong class="text-gray-600">Correo electrónico:</strong>
                                                <input type="email" 
                                                    name="email" 
                                                    value="{{ old('email') }}" 
                                                    placeholder="Ingresa tu correo" 
                                                    required="true" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group px-2">
                                                <strong class="text-gray-600">Teléfono móvil:</strong>
                                                <input type="text" 
                                                    name="phone" 
                                                    value="{{ old('phone') }}" 
                                                    placeholder="Ingresa tu móvil" 
                                                    required="true" 
                                                    maxlength="10" 
                                                    onkeypress="return valideKey(event);" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                                <button type="submit" class="btn btn-primary">Pagar este curso</button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
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

        @if ($errors->any())
            <script type="text/javascript">
                var html ='';
                @foreach ($errors->all() as $error)
                    html +='{{ @$error }}\n';
                @endforeach
                swal({
                    title: "Mensaje!",
                    text: html,
                    icon: "warning",
                    button: "OK",
                    dangerMode: true,
                    closeOnClickOutside: false
                });
            </script>
        @endif

        <script type="text/javascript">
            function valideKey(evt){
                var code = (evt.which) ? evt.which : evt.keyCode;
                if(code==8) {
                    return true;
                } else if(code>=48 && code<=57) {
                    return true;
                } else{
                    return false;
                }
            }
        </script>

    </body>
</html>
