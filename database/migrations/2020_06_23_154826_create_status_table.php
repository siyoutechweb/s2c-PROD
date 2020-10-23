<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusTable extends Migration
{

    public function up()
    {
        Schema::create('status', function(Blueprint $table) {
            $table->increments('id');
            $table->string('statut_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('status');
    }
}
