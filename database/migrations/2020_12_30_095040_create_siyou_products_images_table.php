<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiyouProductsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siyou_products_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('siyou_product_id')->unsigned();
            $table->foreign('siyou_product_id')->references('id')->on('siyou_products')->onDelete('cascade');
            $table->string('img_url')->nullable();
            $table->string('img_name')->nullable();
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
        Schema::dropIfExists('siyou_products_images');
    }
}
