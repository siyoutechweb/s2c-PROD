<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBase;

class ProductBaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    {
        // $faker = Factory::create();
        // foreach (range(1,9) as $index) {
        //     DB::table('product_base')->insert([
        //         'product_name' => 'product'.$index,
        //         'product_description' => 'Product description' .$index,
        //         'category_id' => $faker->numberBetween($min = 1, $max = 6),
        //         'taxe_rate'=> 0.22,
        //         'shop_owner_id'=> 5,
        //         'supplier_id'=> $faker->randomElement([1,2]),
        //         'brand_id'=> $faker->randomElement([1,2,3,4]),
        //         'chain_id'=> $faker->randomElement([1,2]),
        //         ]);
        // }

        // after

        $path = 'database/seeds/s2cSqlSeeders/S2C_product_base.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('product_base table seeded!');

        // {$path = 'database/seeds/SQLFiles/ProductBase.sql';
        //     DB::unprepared(file_get_contents($path));
        //     $this->command->info('Product base table seeded!');
        // }
    }
    
}
