<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProductDiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_discount', function (Blueprint $table) {
            //
            if (Schema::hasColumn('product_discount', 'discount_value'))

        {

            Schema::table('product_discount', function (Blueprint $table)

            {

                $table->dropColumn('discount_value');

            });

        }
        $table->float('discount_value1')->after('product_id');
        $table->float('discount_value2')->after('discount_value1');
        

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
            //
            $table->dropColumn('discount_value1');
            $table->dropColumn('discount_value2');
        });
    }
}
