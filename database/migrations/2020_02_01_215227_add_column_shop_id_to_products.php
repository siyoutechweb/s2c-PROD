<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnShopIdToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('shop_id')->unsigned()->after('shop_owner_id')->nullable();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->integer('chain_id')->unsigned()->after('shop_id')->nullable();
            $table->foreign('chain_id')->references('id')->on('chains');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table-> dropColumn('shop_id');
        });
    }
}
