<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryCriteriaTable extends Migration
{

    public function up()
    {
        Schema::create('category_criteria', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('criteria_id');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('category_criteria');
    }
}
