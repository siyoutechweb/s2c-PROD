<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class SupplierTableSeeder extends Seeder
{
    /**
     * 
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 30) as $index) {
            DB::table('suppliers')->insert([
                'first_name' =>$faker->firstName,
                'last_name' => $faker->lastName,    
                'email' => $faker->email,
                // 'contact'=> $faker->e164PhoneNumber,
                'img_url'=> "https://sliceedit.com/images/avatar.png",
                'latitude'=> 36.8480320000000,
                'longitude'=> 10.1967710000000,

                ]);
        }
    }
}
