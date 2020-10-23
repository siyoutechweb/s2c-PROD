<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{

    public function up()
    {
        Schema::create('menu', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('chain_id')->nullable();
            $table->integer('products')->nullable();
            $table->integer('purchased')->nullable();
            $table->integer('my_wishlist')->nullable();
            $table->integer('invalid_orders')->nullable();
            $table->integer('valid_orders')->nullable();
            $table->integer('paid_orders')->nullable();
            $table->integer('add_product')->nullable();
            $table->integer('product_list')->nullable();
            $table->integer('affect_discount')->nullable();
            $table->integer('discounted_products_list')->nullable();
            $table->integer('member_list')->nullable();
            $table->integer('level_list')->nullable();
            $table->integer('shop_managers_list')->nullable();
            $table->integer('add_new_shop_manager')->nullable();
            $table->integer('store_list')->nullable();
            $table->integer('add_new_store')->nullable();
            $table->integer('suppliers_list')->nullable();
            $table->integer('inventory')->nullable();
            $table->integer('stock_management')->nullable();
            $table->integer('my_account')->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('menu');
    }
}
