<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Products\GetProductsController;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $faker = Faker::create();
        foreach (range(1, 200) as $index) {
            DB::table('products')->insert([
                'product_name' => $faker->numerify('Product ####'),
                'product_barcode' => GetProductsController::newBarcode(),
                // $faker->numberBetween($min = 2000000000000, $max = 2000999999999) ,
                'category_id' => 4,
                'product_image'=> "https://media-exp1.licdn.com/dms/image/C4D0BAQGjCiqglI2oUw/company-logo_200_200/0?e=2159024400&v=beta&t=Tj739azJkBXu_C1ZU8s9aBaSX1Kamz6igqn7he2Prms",
                'unit_price'=>$faker->randomFloat($nbMaxDecimals = 2, $min = 10, $max =1000),
                'cost_price'=>$faker->randomFloat($nbMaxDecimals = 2, $min = 3, $max = 10),
                'product_quantity'=> $faker->numberBetween($min = 10, $max = 100),
                'supplier_id'=> $faker->numberBetween($min = 1, $max = 10),
                'shop_owner_id'=>1,
                'shop_id'=> 1,
                'chain_id'=>2
                ]);
        }

        // After

        // $path = 'database/seeds/s2cSqlSeeders/S2C_products.sql';
        // DB::unprepared(file_get_contents($path));
        // $this->command->info('products table seeded!');


        
    }
    public static function shopOwners()
    {
        $shop_owner=User::whereHas('role', function ($query) {
            $query->where('name', 'ShopOwner')->distinct();
        })->pluck('id');
        return $shop_owner;
    }
   
}
