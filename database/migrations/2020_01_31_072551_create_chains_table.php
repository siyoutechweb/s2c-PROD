<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChainsTable extends Migration
{

    public function up()
    {
        Schema::create('chains', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id')->nullable();
            $table->string('chain_name');
            $table->string('adress');
            $table->string('contacte')->nullable();
            $table->string('chain_mobile')->nullable();
            $table->string('chain_telephone')->nullable();
            $table->string('chain_opening_hours')->nullable();
            $table->string('chain_close_hours')->nullable();
            $table->string('chain_trafic_line')->nullable();
            $table->string('chain_img')->nullable();
            $table->string('chain_lng')->nullable();
            $table->string('chain_lat')->nullable();
            $table->string('approved')->default('1');
            $table->string('fattura_format')->nullable();
            $table->integer('next_format_num')->nullable();
            $table->string('chain_ip')->nullable();
            $table->unsignedInteger('chain_district_id')->nullable();
            $table->string('chain_district_info')->nullable();
            $table->foreign('store_id')->references('id')->on('shops');
            $table->unsignedInteger('shop_owner_id')->nullable();
            $table->foreign('shop_owner_id')->references('id')->on('users');
            $table->unsignedInteger('manager_id')->nullable();
            $table->foreign('manager_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::drop('chains');
    }
}
