<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuickDiscountTable extends Migration
{

    public function up()
    {
        Schema::create('quick_discount', function(Blueprint $table) {
            $table->increments('id');
            $table->string('product_barcode')->nullable();
            $table->integer('chain_id')->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('quick_discount');
    }
}
