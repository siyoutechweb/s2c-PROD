<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashRegisterTable extends Migration
{

    public function up()
    {
        Schema::create('cash_registers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('cassa_id')->nullable()->unique();
            $table->text('token')->nullable();
            $table->integer('chain_id')->unsigned()->nullable();
            $table->timestamps();

           

        });
    }

    public function down()
    {
        Schema::drop('cash_register');
    }
}
