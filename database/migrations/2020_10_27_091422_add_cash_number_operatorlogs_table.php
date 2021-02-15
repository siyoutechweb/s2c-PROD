<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCashNumberOperatorlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('operatorlogs', function (Blueprint $table) {
            //
            $table->integer('cash_number')->default(0)->comment("????");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('operatorlogs', function (Blueprint $table) {
            //
        });
    }
}
