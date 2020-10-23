<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopSupplierPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_supplier_payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('days')->default(0);
            $table->integer('store_id')->unsigned()->nullable();
            $table->foreign('store_id')->references('id')->on('shops')->onDelete('cascade');
            $table->integer('chain_id')->unsigned()->nullable();
            $table->foreign('chain_id')->references('id')->on('chains')->onDelete('cascade');
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
        Schema::dropIfExists('shop_supplier_payment_methods');
    }
}
