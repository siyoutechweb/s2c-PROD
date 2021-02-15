<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductWarningHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_warning_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('store_id')->nullable();
            $table->unsignedInteger('chain_id')->nullable();
            $table->Integer ('product_id')->default(0);
            $table->Integer ('warn_quantity')->default(0);
            $table->Integer ('inventory')->default(0);
            $table->date('days');
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
        Schema::dropIfExists('product_warning_history');
    }
}