<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToPurchaseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_products', function (Blueprint $table) {
            //
            $table->integer('quick_order_id')->unsigned()->nullable();
            $table->foreign('quick_order_id')->references('id')->on('quick_purchase_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_products', function (Blueprint $table) {
            //
        });
    }
}
