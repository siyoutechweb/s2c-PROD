<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiyouProductsPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siyou_products_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id');
            $table->foreign('package_id')->references('id')->on('siyou_packages')->onDelete('cascade');
            $table->integer('product_id');
            $table->foreign('product_id')->references('id')->on('siyou_products')->onDelete('cascade');
            $table->integer('product_quantity')->default(1);
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
        Schema::dropIfExists('siyou_products_packages');
    }
}
