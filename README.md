<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## A cerca de Laravel

Laravel es un marco de aplicación web con una sintaxis elegante y expresiva. Un marco web proporciona una estructura y un punto de partida para crear su aplicación, lo que le permite concentrarse en crear algo sorprendente mientras nos preocupamos por los detalles.

[Visitar la página de Laravel](https://laravel.com/).


## A cerca de este proyecto

Esta es la realización de un proyecto sencillo como ejemplo para implementar una tienda virtual desarrollada en Laravel 8 con integración de la pasarela de pagos [<b>Placetopay</b>](https://www.placetopay.com/web/) para los procesos de transacciones digitales.<br>
<p>Este proyecto esta inicializado con el framework Laravel en su versión 8.x Se pueden implementar grandes proyectos a partir de este DEMO.</p><br>


## Instalación

Puedes clonar este repositorio desde [<b>GitHub: alfonsochb/tienda-placetopay</b>](https://github.com/alfonsochb/tienda-placetopay)

1. Debes abrir una terminal cmd o GitBach y ubicarte en el directorio raíz de tu servidor local de PHP.

2. Realizar la clonación del proyecto de ejemplo
    ```bash
    git clone https://github.com/alfonsochb/tienda-placetopay.git
    ```

3. Desplazar, ubicar la terminal dentro del proyecto descargado
    ```bash
    cd tienda-placetopay
    ```

4. Ejecutar composer para la instalación de las dependencias
    ```bash
    composer install
    ```

5. Tomar una copia del archivo <b>.env.example</b> y guardarlo en el mismo directorio con el nombre <b>.env</b>
<br>Es posible que no se visualice el archivo <b>.env.example</b> si esto ocurre, intente habilitar en su explorador la vista de archivos ocultos.

6. Generar la clave semilla que requiere el framework Laravel.
    ```bash
    php artisan key:generate
    ```

7. Crear la base de datos con el nombre que prefieras y actualizar los datos en las variables de entorno en el archivo <b>.env</b>
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=
    ```

7. Adicionar las siguientes variables de entorno en el archivo <b>.env</b> y configurar con las que provee <b>Placetopay</b>.
También puedes consultar en la documentación de la página `https://placetopay.github.io/web-checkout-api-docs` allí encontrarás las credenciales de acceso en modo de pruebas.
    ```bash
    PLACETOPAY_LOGIN='**************************'
    PLACETOPAY_TRANKEY='**************************'
    ```

8. Ejecutar las migraciones para crear las tablas
    ```bash
    php artisan migrate
    ```

9. Cargar los productos que se han puesto de ejemplo, en el alcance de este proyecto <b>DEMO</b>.
    ```bash
    php artisan db:seed --class=ProductSeeder
    ```

10. Abre el proyecto en tu navegador `https://tuproyectoclonado.com/` y si has realizado correctamente todos los pasos mencionados, entonces podrás ver una interfaz de tienda virtual de ejemplo como la que aparece en la imagen, con un proyecto funcional.
<p align="center"><img src="https://i.postimg.cc/zvYJ5JCC/Captura-Tienda.png" height="400"></p>
<br>

## Archivos importantes

    ```php 
    \config\app_payments.php
    \App\Repositories\PlacetoPay\PlacetoPay.php
    \App\Http\Controllers\ClientsController.php
    \App\Http\Controllers\OrdersController.php
    \App\Http\Controllers\ProductsController.php
    \App\Models\*
    ```
<br>

## Reconocimiento

Autor: [Alfonso Chávez Baquero](https://alfonsochb.com/?target=_blank)