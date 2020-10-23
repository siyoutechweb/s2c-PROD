<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_product', function (Blueprint $table) {
            $table->increments('id');
            $table->date('days');
            $table->integer('chain_id')->unsigned();
            $table->foreign('chain_id')
            ->references('id')->on('chains')
            ->onDelete('cascade');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')
            ->references('id')->on('products')
            ->onDelete('cascade');
            $table->float('price',10,2)->comment('price');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onDelete('cascade');
            $table->integer('sales')->comment('every days sales');
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
        Schema::dropIfExists('statistic_product');
    }
}