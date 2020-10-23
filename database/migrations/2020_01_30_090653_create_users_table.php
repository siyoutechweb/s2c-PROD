<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 255);
            $table->string('password', 255);
            $table->string('contact')->nullable();
            $table->string('birthday')->nullable();
            $table->longText('token')->nullable();
            $table->integer('activated_account')->default(0);
            $table->integer('shop_owner_id')->nullable();
            $table->unsignedInteger('role_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('role_id')->references('id')->on('roles');

        });
    }

    public function down()
    {
        Schema::drop('users');
    }
}
