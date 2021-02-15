<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{

    public function up()
    {
        Schema::create('discounts', function(Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::drop('discounts');
    }
}
