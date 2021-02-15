<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuickScanTable extends Migration
{

    public function up()
    {
        Schema::create('quick_print', function(Blueprint $table) {
            $table->increments('id');
            $table->string('product_barcode')->nullable();
            $table->integer('chain_id')->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('quick_scan');
    }
}
