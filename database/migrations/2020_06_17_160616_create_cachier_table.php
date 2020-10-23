<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCachierTable extends Migration
{

    public function up()
    {
        Schema::create('cachiers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->unsignedInteger('store_id')->nullable();
            $table->unsignedInteger('chain_id')->nullable();
            $table->string('contact', 255)->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(false);
            // $table->foreign('store_id')->references('id')->on('shopes');
            // $table->foreign('chain_id')->references('id')->on('chains');

        });
    }

    public function down()
    {
        Schema::drop('cachiers');
    }
}
