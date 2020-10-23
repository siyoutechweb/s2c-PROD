<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('product_item_order', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')
      ->references('id')->on('products')
      ->onDelete('cascade');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')
      ->references('id')->on('orders')
      ->onDelete('cascade');
            $table->integer('order_item_quantity')->unsigned()->nullable();
            $table->integer('order_item_amount')->unsigned()->nullable();
            $table->integer('order_item_payment_amount')->unsigned()->nullable();
            
            $table->timestamps();
            // $table->foreign('product_id')
            //     ->references('id')
            //     ->on('product_items');
            // $table->foreign('order_id')
            //     ->references('id')
            //     ->on('orders');
            // // Schema declaration
            // // Constraints declaration

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('product_item_order');
    }
}
