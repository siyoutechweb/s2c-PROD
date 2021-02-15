<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberLevelsTable extends Migration
{

    public function up()
    {
        Schema::create('member_levels', function(Blueprint $table) {
            $table->increments('id');
            $table->string('level');
            $table->string('description')->nullable();
            $table->float('discount_percent')->nullable();
            $table->float('start_point')->nullable();
            $table->float('end_point')->nullable();
            $table->float('increment_point')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::drop('member_levels');
    }
}
