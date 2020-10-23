<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBaseTable extends Migration
{

    public function up()
    {
        Schema::create('product_base', function(Blueprint $table) {
            $table->increments('id');
            $table->string('product_name');
            $table->string('product_description')->nullable();
            $table->float('taxe_rate')->default(22);
            $table->integer('category_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('chain_id')->nullable();
            $table->integer('shop_owner_id')->nullable();
            $table->timestamps();
            

        });
    }

    public function down()
    {
        Schema::drop('product_base');
    }
}
