<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{

    public function up()
    {
        Schema::create('suppliers', function(Blueprint $table) {
            $table->increments('id');
            // $table->integer('shop_owner_id')->nullable();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('description')->nullable();
            $table->string('email', 255);
            $table->string('img_url')->nullable();
            $table->string('img_name')->nullable();
            $table->decimal('latitude', 17, 13)->nullable();
            $table->decimal('longitude', 17, 13)->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop('suppliers');
    }
}
