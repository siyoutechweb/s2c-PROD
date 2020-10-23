<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\Shop;
use App\Models\User;
use App\Models\Chain;

class ChainsTableSeeder extends Seeder
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
//             DB::table('chains')->insert([
//                 'chain_name' => $faker->numerify('Chain ###'),
//                 'adress' => $faker->address,
//                 'chain_opening_hours' => '08:00h',
//                 'chain_close_hours'=> '22:00',
//                 'shop_owner_id'=> $faker->randomElement($this->shopOwners()),
//                 'store_id'=>$faker->randomElement([1,2,3,4,5,6,7,8,9]),
                
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
        $chain= new chain();
        $chain->chain_name = 'Carrefour Market le Passage';
        $chain->adress = 'Tunis -Tunisia';
        $chain->chain_telephone = '70 011 000';
        $chain->chain_opening_hours = '08:00h';
        $chain->chain_close_hours = '22:00';
        $chain->shop_owner_id = user::where('email', 'shop@siyoutech.tn')->value('id');
        $chain->store_id = shop::where('shop_owner_id', $chain->shop_owner_id )->value('id');
        $chain->save();

        $chain1= new chain();
        $chain1->chain_name = 'Carrefour Market ';
        $chain1->adress = 'Les Jardins El Mourouj - tunis -Tunisia';
        $chain1->chain_telephone = '70 248 248';
        $chain1->chain_opening_hours = '08:00h';
        $chain1->chain_close_hours = '22:00';
        $chain1->shop_owner_id = user::where('email', 'shop@siyoutech.tn')->value('id');
        $chain1->store_id = shop::where('shop_owner_id', $chain1->shop_owner_id )->value('id');
        $chain1->save();

        $chain2= new chain();
        $chain2->chain_name = 'monoprix Bardo ';
        $chain2->adress = 'tunis, Tunisia';
        $chain2->chain_telephone = '71 580 415';
        $chain2->chain_opening_hours = '08:00h';
        $chain2->chain_close_hours = '23:00';
        $chain2->shop_owner_id = user::where('email', 'safwen@outlook.fr')->value('id');
        $chain2->store_id = shop::where('shop_owner_id', $chain2->shop_owner_id )->value('id');
        $chain2->save();

        $chain3= new chain();
        $chain3->chain_name = 'monoprix Express ';
        $chain3->adress = 'Place Mizene, Tunis, Tunisia';
        $chain3->chain_telephone = '71 751 429';
        $chain3->chain_opening_hours = '08:00h';
        $chain3->chain_close_hours = '23:00';
        $chain3->shop_owner_id = user::where('email', 'safwen@outlook.fr')->value('id');
        $chain3->store_id = shop::where('shop_owner_id', $chain3->shop_owner_id )->value('id');
        $chain3->save();

        // after
        // $path = 'database/seeds/s2cSqlSeeders/S2C_chains.sql';
        // DB::unprepared(file_get_contents($path));
        // $this->command->info('chains table seeded!');


    }
}
