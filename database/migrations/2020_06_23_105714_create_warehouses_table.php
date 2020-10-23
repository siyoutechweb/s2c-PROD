<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{

    public function up()
    {
        // Schema::create('warehouses', function(Blueprint $table) {
        //     $table->increments('id');
        //     $table->float('storage_space');
        //     $table->string('picking_process');
        //     $table->string('prompt_delivery');
        //     $table->decimal('latitude');
        //     $table->decimal('longitude');
        //     $table->integer('user_id')->unsigned()->index();
        //     $table->foreign('user_id')->references('id')->on('users');

        // });
    }

    public function down()
    {
        // Schema::drop('warehouses');
    }
}
