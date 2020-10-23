<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{

    public function up()
    {
        Schema::create('members', function(Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('gender', 50);
            $table->string('contact');
            $table->string('email');
            $table->string('card_num');
            $table->float('points')->nullable();
            $table->integer('level_id')->unsigned()->nullable();
            $table->integer('store_id')->unsigned()->nullable();
            $table->foreign('level_id')->references('id')->on('member_levels');
            $table->date('expiration_date')->nullable();
            $table->foreign('store_id')->references('id')->on('shops');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::drop('members');
    }
}
