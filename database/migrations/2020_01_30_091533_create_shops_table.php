<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{

    public function up()
    {
        Schema::create('shops', function(Blueprint $table) {
            $table->increments('id');
            $table->string('store_name');
            $table->string('store_name_en')->nullable();
            $table->string('store_name_it')->nullable();
            $table->string('store_area')->nullable();
            $table->string('store_domain')->nullable();
            $table->integer('store_grade_id')->nullable();
            $table->string('store_logo')->nullable();
            $table->string('store_adress')->nullable();
            $table->string('contact')->nullable();
            $table->string('store_longitude')->nullable();
            $table->string('store_latitude')->nullable();
            $table->string('opening_hour')->nullable();
            $table->string('closure_hour')->nullable();
            $table->string('store_ip')->nullable();
            $table->integer('store_is_open')->nullable();
            $table->integer('store_is_selfsupport')->nullable();
            $table->unsignedInteger('shop_owner_id')->nullable();
            $table->foreign('shop_owner_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::drop('shops');
    }
}
