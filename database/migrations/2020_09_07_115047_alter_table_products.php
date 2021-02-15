<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('products', function (Blueprint $table) {
            //
            if (Schema::hasColumn('products', 'warn_quantity'))

        {

            Schema::table('products', function (Blueprint $table)

            {

                $table->dropColumn('warn_quantity');

            });

        }
        $table->integer('warn_quantity')->after('product_quantity')->default(0);
        
        

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
