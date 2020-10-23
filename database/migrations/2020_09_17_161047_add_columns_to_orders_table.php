<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->longText('cart_data')->change();
            $table->float('order_payment_amount')->nullable();
            $table->integer('product_quantity')->nullable();
            $table->integer('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('shops');
            $table->renameColumn('cachier_id', 'cashier_id');
            
            $table->string('operator')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
