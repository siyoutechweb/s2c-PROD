<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableWarehouses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            Schema::dropIfExists('warehouses');
            $table->increments('id');
            
            $table->string('name');
            $table->string('description');
            $table->string('first_responsible');
            $table->string('second_responsible');
            $table->decimal('longitude');
            $table->decimal('latitude');
            $table->integer('shop_owner_id')->unsigned();
            $table->foreign('shop_owner_id')->references('id')->on('users');
            $table->integer('chain_id')->unsigned();
            $table->foreign('chain_id')->references('id')->on('chains');
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
        Schema::dropIfExists('warehouses');
    }
}
