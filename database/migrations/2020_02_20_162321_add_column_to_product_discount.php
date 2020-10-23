<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToProductDiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_discount', function (Blueprint $table) {
            $table->float('percent')->nullable();
            $table->float('amount')->nullable();
            $table->float('fix_price')->nullable();
            $table->float('n_value')->nullable();
            $table->float('m_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_discount', function (Blueprint $table) {
            $table-> dropColumn ('percent');
            $table-> dropColumn ('amount');
            $table-> dropColumn ('fix_price');
            $table-> dropColumn ('n_value');
            $table-> dropColumn ('m_value');
        });
    }
}
