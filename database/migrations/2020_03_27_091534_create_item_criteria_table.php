<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemCriteriaTable extends Migration
{

    public function up()
    {
        Schema::create('item_criteria', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_item_id');
            $table->integer('criteria_id');
            $table->integer('criteria_unit_id')->nullable();
            $table->string('criteria_value');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('item_criteria');
    }
}
