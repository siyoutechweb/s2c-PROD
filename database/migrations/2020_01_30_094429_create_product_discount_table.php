<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDiscountTable extends Migration
{

    public function up()
    {
        Schema::create('product_discount', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('discount_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->date('start_date');
            $table->date('finish_date');
            $table->integer('discount_barcode');
            $table->foreign('discount_id')->references('id')->on('discounts');
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('product_discount');
    }
}
