<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopChainFunctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_chain_functions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('store_id')->nullable()->comment('the store_id');
            $table->unsignedInteger('chain_id')->nullable()->comment('the chain_id');
            $table->tinyInteger('no_fiscal_invoice')->default(0)->comment('????????');
            $table->tinyInteger('upload_non_fiscal')->default(0)->comment('???????');
            $table->tinyInteger('quick_scan_collect')->default(0)->comment('??????');
            $table->tinyInteger('excel_copy_multi_chain')->default(0)->comment('?????????????');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_chain_function');
    }
}