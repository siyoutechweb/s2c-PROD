<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupplierIdToStatisticProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('statistic_product', function (Blueprint $table) {
            //
            $table->integer('supplier_id')->unsigned()->comment("???ID");
        });
        DB::statement("update statistic_product a inner join products b on a.product_id=b.id set a.supplier_id=b.supplier_id");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('statistic_product', function (Blueprint $table) {
            //
        });
    }
}
