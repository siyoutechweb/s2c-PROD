<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('facebook_id')->nullable();
            $table->bigInteger('google_id')->nullable();
            $table->text('cover_img_url')->nullable();
            $table->string('cover_img_name')->nullable();
            $table->text('profile_img_url')->nullable();
            $table->string('profile_img_name')->nullable();
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_company')->nullable();
            $table->text('billing_address_1')->nullable();
            $table->text('billing_address_2')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('shipping_first_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->string('shipping_company')->nullable();
            $table->text('shipping_address_1')->nullable();
            $table->text('shipping_address_2')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('code')->nullable();
            $table->bigInteger('verify_types')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['facebook_id','google_id','cover_img_url',
            'cover_img_name','profile_img_url','profile_img_name','billing_first_name',
            'billing_last_name','billing_company','billing_address_1','billing_address_2',
            'billing_country','billing_state','billing_city','billing_postal_code',
            'billing_email','billing_phone','shipping_first_name','shipping_last_name',
            'shipping_company','shipping_address_1','shipping_address_2','shipping_country',
            'shipping_state','shipping_city','shipping_postal_code','shipping_email','shipping_phone',
            'code','verify_types']);
        });
    }
}
