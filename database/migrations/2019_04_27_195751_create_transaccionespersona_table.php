<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaccionespersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaccionespersona', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre',100)->nullable()->default(NULL);
            $table->string('apellido',100)->nullable()->default(NULL);
            $table->string('email',200)->nullable()->default(NULL);
            $table->string('telefono',50)->nullable()->default(NULL);
            $table->string('cuit',20)->nullable()->default(NULL);
            $table->string('cp',10)->nullable()->default(NULL);
            /**
             * Consumidor final
             * Excento
             * Monotributo
             * No responsable}
             * Operaciones Turísmo
             * Responsable Inscripto
             */
            $table->unsignedBigInteger('condicion_iva')->default(0);
            $table->unsignedBigInteger('transaccion_id')->default(0);
            $table->unsignedBigInteger('localidad_id')->default(0);

            $table->foreign('transaccion_id')->references('id')->on('transacciones');
            $table->foreign('localidad_id')->references('id')->on('localidades');
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
        Schema::dropIfExists('transaccionespersona');
    }
}
