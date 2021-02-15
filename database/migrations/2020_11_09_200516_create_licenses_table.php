<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_owner_id')->unsigned()->nullable();
            $table->foreign('shop_owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('max_chains')->default(5);
            $table->integer('max_managers')->default(5);
            $table->integer('max_cachiers')->default(5);
            $table->date('start_date');
            $table->date('finish_date');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licenses');
    }
}
