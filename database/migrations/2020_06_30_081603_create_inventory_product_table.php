<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryProductTable extends Migration
{

    public function up()
    {
        Schema::create('inventory_product', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_id');
            $table->integer('product_id');
            $table->integer('product_quantity')->nullable();
            $table->integer('arrived_quantity')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('inventory_product');
    }
}
