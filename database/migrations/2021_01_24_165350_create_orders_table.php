<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Ya existía una base de datos antes de conocer sobre pasarelas de pago.
     * Se adicionan los campos para integrar las pasarelas de pago y que la tabla de ordenes sea relacional.
     * La información que existía antes de la pasarela de pagos se exporta a una tabla ordes_historico.
     * La información de los campos: cliente, email, móvil, producto, costo; por razón de que a futuro cambie
     * la información de producto en actualización de nombre o costo, altera la veracidad de futuros reportes.
     * Son nuevos los campos: client_id, product_id, producto, costo, referencia, message, request_id, process_url.
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id')->comment('Relación con cliente.');
            $table->unsignedBigInteger('product_id')->comment('Relación con producto.');
            $table->string('customer_name')->comment('Replicado por futuros cambios en relación.');
            $table->string('customer_email')->comment('Replicado por futuros cambios en relación.');
            $table->string('customer_mobile')->comment('Replicado por futuros cambios en relación.');
            $table->string('product_name', 100)->comment('Replicado por futuros cambios en relación.');
            $table->double('product_cost', 8, 2)->comment('Costo en el instante de compra.');
            $table->string('order_ref', 100)->comment('Referencia de órden.');
            $table->integer('request_id')->comment('Identificador de sesión pasarela');
            $table->string('pass_message')->nullable()->comment('Mensaje de pasarela de pagos.');
            $table->string('process_url')->nullable()->comment('URL de procesamiento');
            $table->string('status', 10)->nullable()->comment('estados; CREATED, PAYED, REJECTED');
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
