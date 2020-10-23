<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_order', function (Blueprint $table) {
            $table->increments('id');
            $table->date('days')->nullable(false)->comment('days such as 2020-08-26')->index();
            $table->integer('chain_id')->nullable()->unsigned()->comment('the chain_id');
            $table->foreign('chain_id')
            ->references('id')->on('chains')
            ->onDelete('cascade');
            $table->float('income',12,2)->comment('total income');
            $table->float('paid',12,2)->default(0);
            $table->float('confirmed',12,2)->default(0);
            $table->float('pended',12,2)->default(0);
            $table->float('card',12,2)->default(0)->comment('payment by cash');
            $table->float('check',12,2)->default(0)->comment('payment by check');
            $table->float('cash',12,2)->default(0)->comment('payment by cash');
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
        Schema::dropIfExists('statisitc_order');
    }
}
