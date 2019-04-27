<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaccionesprodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaccionesprod', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cantidad')->default(0);
            $table->integer('consultar')->default(0);//Productos a consultar por falta de STOCK
            $table->unsignedBigInteger('transaccion_id')->default(0);
            $table->unsignedBigInteger('producto_id')->default(0);

            $table->foreign('producto_id')->references('id')->on('productos');
            $table->foreign('transaccion_id')->references('id')->on('transacciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaccionesprod');
    }
}
