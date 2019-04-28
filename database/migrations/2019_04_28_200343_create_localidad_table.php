<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localidad', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre',200)->nullable()->default(NULL);
            $table->string('codigopostal',20)->nullable()->default(NULL);
            $table->unsignedBigInteger('provincia_id')->default(0);

            $table->foreign('provincia_id')->references('id')->on('provincia')->onDelete('cascade');
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
        Schema::dropIfExists('localidad');
    }
}
