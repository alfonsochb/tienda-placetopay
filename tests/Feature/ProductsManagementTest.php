<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;

class ProductsManagementTest extends TestCase
{
    
    use RefreshDatabase;

    /** @test */
    public function obtenerListadoProductosTest()
    {
        # Desabilitar capturador de excepción.
        $this->withoutExceptionHandling();

        # Crear unos datos ficticios.
        $product = Product::factory()->create();

        # Se asumen 3 registros de prueba.
        $products = Product::factory()->count(3)->create();

        # ¿Esta haciendo bien la petición a la URL que lista los productos.?
        $response = $this->get('/products');

        # ¿Encontro el recurso solicitado?
        $response->assertStatus(200);

        # ¿Todo funciona de forma correcta.?
        $response->assertOk();

        # ¿ La vista de productos esta en la vista de inicio?
        $response->assertViewIs('welcome');

    }


    /** @test */
    public function obtenerVistaDetalleProductoTest()
    {
        # Desabilitar capturador de excepción.
        $this->withoutExceptionHandling();

        # Crear unos datos ficticios.
        $product = Product::factory()->create();

        # Se asume 1 registro de prueba.
        $product = Product::factory()->count(1)->create();

        # ¿Esta haciendo bien la petición a la URL detalle de un producto.?
        $response = $this->get('products/1');

        # ¿Encontro el recurso solicitado?
        $response->assertStatus(200);

        # ¿Todo funciona de forma correcta.?
        $response->assertOk();

        # ¿ La vista de productos esta en la vista de inicio?
        $response->assertViewIs('products.show');

    }


}
