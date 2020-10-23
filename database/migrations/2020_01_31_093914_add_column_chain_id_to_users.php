<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnChainIdToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // public function up()
    // {
    //     Schema::table('users', function (Blueprint $table) {
    //         $table->unsignedInteger('chain_id')->after('shop_Owner_id')->nullable();
    //         $table->foreign('chain_id')->references('id')->on('chains'); 
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::table('users', function (Blueprint $table) {
    //         $table-> dropColumn ('chain_id');
    //     });
    // }
}
