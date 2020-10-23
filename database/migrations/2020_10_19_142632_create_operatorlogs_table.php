<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperatorlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operatorlogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('operator_id')->default(0)->comment('???id');
			$table->string('op_cashier_name',100)->default(null)->comment('?????');
            $table->integer('shop_owner_id')->default(0)->comment('store_id');
            $table->integer('store_id')->default(0)->comment('store_id');
            $table->string('op_description')->default('');
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
        Schema::dropIfExists('operatorlogs');
    }
}
