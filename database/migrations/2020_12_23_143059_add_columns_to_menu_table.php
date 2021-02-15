<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu', function (Blueprint $table) {
            //
            $table->integer('b2s_order_management')->nullable();
            $table->integer('discount_list')->nullable();
            $table->integer('inventory_history')->nullable();
            $table->integer('s2c_orders_list')->nullable();
            $table->integer('B2s')->nullable();
            $table->integer('s2c')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu', function (Blueprint $table) {
            //
        });
    }
}
