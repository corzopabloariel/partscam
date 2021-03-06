<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo',20)->nullable()->default(NULL);
            $table->string('nombre',100)->nullable()->default(NULL);
            $table->string('mercadolibre',150)->nullable()->default(NULL);
            $table->string('orden',10)->nullable()->default(NULL);
            $table->unsignedBigInteger('categoria_id')->nullable()->default(NULL);
            $table->unsignedBigInteger('familia_id')->nullable()->default(NULL);

            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('set null');
            $table->foreign('familia_id')->references('id')->on('familias')->onDelete('set null');
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
        Schema::dropIfExists('productos');
    }
}
