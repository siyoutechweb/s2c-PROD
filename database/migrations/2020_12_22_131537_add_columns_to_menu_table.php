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
            $table->integer('add_quick_purchase')->nullable();
            $table->integer('purchase_order')->nullable();
            $table->integer('siyou_suppliers')->nullable();
            $table->integer('my_suppliers')->nullable();

            $table->integer('siyou_categories')->nullable();
            $table->integer('my_category')->nullable();
            $table->integer('promotion_history')->nullable();
            $table->integer('promotion_list')->nullable();
            $table->integer('discount_history')->nullable();
            $table->integer('sales_funds')->nullable();
            $table->integer('accounts_payable')->nullable();
            $table->integer('payment_methods')->nullable();
            $table->integer('shop_operators_list')->nullable();
            $table->integer('add_shop_operator')->nullable();
            $table->integer('shop_cashiers_list')->nullable();
            $table->integer('add_shop_cashier')->nullable();
            $table->integer('advertisement')->nullable();
            $table->integer('inventory_management')->nullable();
            
            $table->integer('warehouse_management')->nullable();
            $table->integer('returned_goods')->nullable();

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
