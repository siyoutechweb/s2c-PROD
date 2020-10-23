<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class ItemCriteriaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        foreach (range(1,26) as $index) {
            DB::table('item_criteria')->insert([
                'product_item_id'=> $faker->numberBetween($min = 1, $max = 20),
                'criteria_id'=> $faker->numberBetween($min = 1, $max = 2),
                'criteria_value'=> 'value'.$index
                ]);
        }
    }
}
