<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Client;


/**
* @category Sistema integral de pasarela de pago WebCheckOut Placetopay.
* @since Creado: 2021-02-02
* @author Ing. Alfonso Chávez Baquero <alfonso.chb@gmail.com>
*/
class AllManagementTest extends TestCase
{
    
    use RefreshDatabase;


    /** @test */
    public function obtenerListadoProductosTest()
    {
        # Desabilitar capturador de excepción.
        $this->withoutExceptionHandling();

        # Crear unos datos ficticios.
        $product = Product::factory()->create();

        # Se asumen 10 registros de prueba.
        $products = Product::factory()->count(10)->create();
        //print_r($products);

        # ¿Esta haciendo bien la petición a la URL que lista los productos.?
        $response = $this->get(route("products.index"))->assertOk();

        # ¿Encontro el recurso solicitado?
        $response->assertStatus(200);

        # ¿ La vista de productos esta en la vista de inicio?
        $response->assertViewIs('welcome');
    }



    /** @test */
    public function obtenerDetalleProductoTest()
    {
        # Desabilitar capturador de excepción.
        $this->withoutExceptionHandling();

        # Crear unos datos ficticios.
        $product = Product::factory()->create();

        # Se asume 10 registros de prueba.
        $product = Product::factory()->count(10)->create();

        # ¿Esta haciendo bien la petición a la URL detalle de un producto.?
        //$response = $this->get('products/8')->assertOk();
        $response = $this->get(
            route("products.show", random_int(1, 10))
        )->assertOk();

        $info = Product::findOrFail( random_int(1, 10) );
        print_r("Producto: ".$info->product_name);

        # ¿Encontro el recurso solicitado?
        $response->assertStatus(200);

        # ¿ La vista de productos esta en la vista de inicio?
        $response->assertViewIs('products.show');
    }


    /** @test */
    public function nuevoClienteTest()
    {
        # Desabilitar capturador de excepción.
        $this->withoutExceptionHandling();

        $client = Client::factory()->create([
            '_token' => csrf_token(),
            'names' => 'Test',
            'surnames' => 'Test2',
            'email' => 'testemail@example.com',
            'phone' => '3218888888',
        ]);
        //Enviar post request
        /*$response = $this->post(route('clients.store'), [
            '_token' => csrf_token(),
            'names' => 'Test',
            'surnames' => 'Test2',
            'email' => 'testemail@example.com',
            'phone' => '3218888888',
        ]);*/

    }


}
