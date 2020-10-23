<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountValueToDisountProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_discount', function (Blueprint $table) {
            $table-> dropColumn ('percent');
            $table-> dropColumn ('amount');
            $table-> dropColumn ('fix_price');
            $table-> dropColumn ('n_value');
            $table-> dropColumn ('m_value');
            $table-> dropColumn ('discount_price');
            $table->decimal('discount_value',8,2)->nullable()->after('product_id');
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
            $table->dropColumn ('discount_value');
        });
    }
}
