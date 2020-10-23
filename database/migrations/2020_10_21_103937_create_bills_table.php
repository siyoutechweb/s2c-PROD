<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('store_id')->nullable();
            $table->unsignedInteger('chain_id')->nullable();
            $table->string('company_country',255)->default('')->comment('?????');
            $table->string('company',100)->default('')->comment('??');
            $table->string('person_tax_code',255)->default('')->comment('?????');
            $table->string('vat_number',255)->default('')->comment('????');
            $table->string('country',255)->default('')->comment('??');
            $table->string('province',255)->default('')->comment('??');
            $table->string('city',255)->default('')->comment('??');
            $table->string('address',255)->default('')->comment('??');
            $table->string('capital',255)->default('')->comment('??');
            $table->string('pec',255)->default('')->comment('email');
            $table->string('code_destination',255)->default('')->comment('?????');
            $table->string('phone', 20)->default('')->comment('???');
            $table->string('fax', 20)->default('')->comment('???');
            $table->string('email', 20)->default('')->comment('???');
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
        Schema::dropIfExists('bills');
    }
}
