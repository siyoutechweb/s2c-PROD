<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopSupplierTable extends Migration
{

    public function up()
    {
        Schema::create('shop_supplier', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id')->unsigned();
            $table->integer('supplier_id')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('shop_supplier');
    }
}
