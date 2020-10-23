<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProductItemOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_item_order', function (Blueprint $table) {
            //
            $table->integer('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('shops');
            $table->integer('chain_id')->unsigned();
            $table->foreign('chain_id')->references('id')->on('chains');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->float('item_rate')->nullable();
            $table->integer('order_item_return')->nullable();
            $table->decimal('item_unit_price')->nullable();
            $table->string('item_name');
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_item_order', function (Blueprint $table) {
            //
        });
    }
}
