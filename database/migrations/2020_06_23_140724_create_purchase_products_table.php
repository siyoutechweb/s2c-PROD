<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseProductsTable extends Migration
{

    public function up()
    {
        Schema::create('purchase_products', function(Blueprint $table) {
            $table->increments('id');
            $table->string('product_name');
            $table->bigInteger('product_barcode');
            $table->string('product_description')->nullable();
            $table->string('product_image')->nullable();
            $table->float('cost_price');
            $table->float('tax_rate')->default(0.022);
            $table->float('product_weight')->nullable();
            $table->float('product_size')->nullable();
            $table->float('product_color')->nullable();
            $table->string('supplier_id')->nullable();
            $table->string('purchase_order_id')->nullable();
            // $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->integer('product_quantity')->nullable();
            $table->unsignedInteger('category_id');
            //$table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedInteger('shop_owner_id');
            // $table->foreign('shop_owner_id')->references('id')->on('users');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('purchase_products');
    }
}
