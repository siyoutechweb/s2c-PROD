<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{

    public function up()
    {
        Schema::create('purchase_orders', function(Blueprint $table) {
            $table->increments('id');
            $table->string('order_ref')->nullable();
            $table->date('order_date')->nullable();
            $table->date('required_date')->nullable();
            $table->date('shipping_date')->nullable();
            $table->string('shipping_type')->nullable();
            $table->decimal('shipping_price',8,2)->nullable();
            $table->string('shipping_adresse')->nullable();
            $table->string('shipping_country')->nullable();
            $table->decimal('order_price',8,2);
            $table->float('order_weight')->nullable();
            $table->unsignedInteger('supplier_id');
            // $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->unsignedInteger('shop_owner_id');
            // $table->foreign('shop_owner_id')->references('id')->on('users');
            $table->Integer('statut_id');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('purchase_orders');
    }
}
