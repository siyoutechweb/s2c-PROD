<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStoreIdToMemberLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_levels', function (Blueprint $table) {
            $table->integer('store_id')->unsigned()->after('end_point')->nullable();
            $table->foreign('store_id')->unsigned()->references('id')->on('shops');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_levels', function (Blueprint $table) {
            $table-> dropColumn ('store_id');
        });
    }
}
