<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTable extends Migration
{

    public function up()
    {
        Schema::create('inventory', function(Blueprint $table) {
            $table->increments('id');
            $table->string('batch_number')->nullable();
            $table->string('operator')->nullable();
            $table->string('verifier')->nullable();
            $table->date('date')->nullable();
            $table->integer('warehouse_id')->unsigned()->nullable();
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->integer('shop_owner_id')->unsigned()->nullable();
            $table->integer('operator_status')->unsigned()->nullable();
            // $table->foreign('warehouse_id')->references('id')->on('suppliers');
            // $table->foreign('supplier_id')->references('id')->on('warehouses');
            // $table->foreign('status_id')->references('id')->on('status');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('inventory');
    }
}
