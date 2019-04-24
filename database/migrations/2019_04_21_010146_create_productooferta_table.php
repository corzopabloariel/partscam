<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoofertaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productooferta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('orden',10)->nullable()->default(NULL);
            $table->float('porcentaje', 8, 2)->default(NULL);
            $table->double('precio', 8, 2)->default(NULL);
            $table->unsignedBigInteger('producto_id')->default(0);

            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
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
        Schema::dropIfExists('productooferta');
    }
}
