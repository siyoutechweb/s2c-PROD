<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiyouOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siyou_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->integer('payment_method_id')->unsigned();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->integer('package_id')->unsigned()->nullable();
            $table->foreign('package_id')->references('id')->on('siyou_packages')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('required_date')->nullable();
            $table->date('shipping_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->float('total_price');
            $table->integer('order_status')->unsigned();
            $table->foreign('order_status')->references('id')->on('siyou_orders_status')->onDelete('cascade');
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
        Schema::dropIfExists('siyou_orders');
    }
}
