<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductItemsTable extends Migration
{

    public function up()
    {
        Schema::create('product_items', function(Blueprint $table) {
            $table->increments('id');
            $table->float('item_online_price')->nullable();
            $table->float('item_offline_price')->nullable();
            // $table->integer('item_package')->nullable();
            // $table->integer('item_box')->nullable();
            $table->float('unit_price')->nullable();
            $table->float('cost_price')->nullable();
            $table->string('item_barcode')->nullable();
            $table->integer('item_warn_quantity')->nullable();
            $table->integer('item_quantity');
            $table->string('item_discount_type')->nullable();
            $table->float('item_discount_price')->nullable();
            $table->float('member_price')->nullable();
            $table->float('member_point')->nullable();
            $table->integer('product_base_id')->unsigned()->nullable();
            $table->foreign('product_base_id')->references('id')->on('product_base');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('product_items');
    }
}
