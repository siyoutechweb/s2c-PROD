<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiyouProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siyou_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name');
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->integer('product_quantity')->nullable();
            $table->integer('unit_price');
            $table->integer('member_price');
            $table->string('img_url')->nullable();
            $table->string('img_name')->nullable();
            $table->string('product_type')->nullable();
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
        Schema::dropIfExists('siyou_products');
    }
}
