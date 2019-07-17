<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image',150)->nullable()->default(NULL);
            $table->string('titulo',70)->nullable()->default(NULL);
            $table->string('subtitulo',60)->nullable()->default(NULL);
            $table->string('orden',3)->nullable()->default(NULL);
            $table->text('detalle')->nullable()->default(NULL);
            
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
        Schema::dropIfExists('servicios');
    }
}
