<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{

    public function up()
    {
        Schema::create('orders', function(Blueprint $table) {
            $table->increments('id');
            $table->float('payment_amount');
            $table->float('member_price');
            $table->integer('chain_id');
            $table->integer('cachier_id')->unsigned();
            // $table->foreign('chains_id')->references('id')->on('chains');
            $table->foreign('cachier_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::drop('orders');
    }
}
