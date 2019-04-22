<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoimagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productoimages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image',100)->nullable()->default(NULL);
            $table->string('orden',3)->nullable()->default(NULL);
            $table->unsignedBigInteger('producto_id');

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
        Schema::dropIfExists('productoimages');
    }
}
