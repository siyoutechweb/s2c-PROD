<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class ProductItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        foreach (range(1,20) as $index) {
            DB::table('product_items')->insert([
                'item_barcode' => $faker->numberBetween($min = 2000000000000, $max = 2000999999999),
                'item_quantity' => $faker->numberBetween($min = 20, $max = 69),
                'product_base_id' => $faker->numberBetween($min = 1, $max =9)
                ]);
        }
    }
}
