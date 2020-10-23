<?php

use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
class ShopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
//     public function run()
//     {
        
//         $faker = Faker::create();
//         foreach (range(1, 100) as $index) {
//             DB::table('shops')->insert([
//                 'store_name' => $faker->numerify('Store ###'),
//                 'store_adress' => $faker->address,
//                 'contact'=>$faker->e164PhoneNumber,
//                 'opening_hour' => '08:00h',
//                 'closure_hour'=> '22:00',
//                 'store_ip'=> '234678969369',
//                 'shop_owner_id'=> $faker->randomElement($this->shopOwners()),
                
                
//                 ]);
//         }
//     }
//     public static function shopOwners()
//     {
//         $shop_owner=User::whereHas('role', function ($query) {
//             $query->where('name', 'ShopOwner')->distinct();
//         })->pluck('id');
//         return $shop_owner;
//     }
// }
    public function run()
    {
    // {
        $shop=new shop();
        $shop->store_name = 'Carrefour Group';
        $shop->store_adress = '33 Avenue Émile Zola, 92100 Boulogne-Billancourt, France';
        $shop->contact = '01 41 04 26 00';
        $shop->opening_hour = '08:00h';
        $shop->closure_hour = '22:00';
        $shop->store_ip = '234678969369';
        $shop->shop_owner_id = user::where('email', 'bahaeddineb@outlook.fr')->value('id');
        $shop->save();

        $shop1=new shop();
        $shop1->store_name = 'monoprix';
        $shop1->store_adress = '16 rue Marc Bloch, 92116 Clichy, France';
        $shop1->contact = '01 78 99 90 00';
        $shop1->opening_hour = '08:00h';
        $shop1->closure_hour = '22:00';
        $shop1->store_ip = '234678923897';
        $shop1->shop_owner_id = user::where('email', 'safwen@outlook.fr')->value('id');
        $shop1->save();
    // }
    // $path = 'database/seeds/s2cSqlSeeders/S2C_shops.sql';
    //     DB::unprepared(file_get_contents($path));
    //     $this->command->info('shops table seeded!');
    }
 }
