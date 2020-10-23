<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCriteriaUnitsTable extends Migration
{

    public function up()
    {
        Schema::create('criteria_units', function(Blueprint $table) {
            $table->increments('id');
            $table->string('unit_name')->nullable();
            // $table->integer('criteria_base_id')->unsigned()->nullable();
            // $table->foreign('criteria_base_id')->references('id')->on('criteria_base');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('criteria_units');
    }
}
