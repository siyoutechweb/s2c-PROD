<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
           $table->float('discount_amount',8,2)->nullable();
           $table->integer('order_number')->nullable();
           $table->bigInteger('card_number')->nullable();
           $table->integer('vip_point')->nullable();
           $table->string('cart_data')->nullable();
           $table->string('invoice')->nullable();

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
            $table->dropColumn(['discount_amount','order_number',
            'card_number','vip_point','cart_data','invoice',
            ]);
        });
    }
}
