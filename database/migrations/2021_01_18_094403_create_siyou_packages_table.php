<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiyouPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siyou_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('package_name');
            $table->string('short_description');
            $table->text('description')->nullable();
            $table->float('package_price');
            $table->float('member_price');
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
        Schema::dropIfExists('siyou_packages');
    }
}
