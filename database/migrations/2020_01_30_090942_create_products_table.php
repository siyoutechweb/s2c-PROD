<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{

    public function up()
    {
        Schema::create('products', function(Blueprint $table) {
            $table->increments('id');
            $table->string('product_name');
            $table->bigInteger('product_barcode');
            $table->string('product_description')->nullable();
            $table->string('product_image')->nullable();
            $table->float('unit_price');
            $table->float('cost_price');
            $table->float('member_price')->nullable();
            $table->float('member_point')->nullable();
            $table->float('tax_rate')->default(0.022);
            $table->float('product_weight')->nullable();
            $table->float('product_size')->nullable();
            $table->float('product_color')->nullable();
            $table->string('supplier_id')->nullable();
            $table->integer('product_quantity')->nullable();
            $table->integer('warn_quantity')->nullable();
            $table->date('expired_date')->nullable();
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('shop_owner_id')->unsigned()->nullable();
            $table->foreign('shop_owner_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::drop('products');
    }
}
