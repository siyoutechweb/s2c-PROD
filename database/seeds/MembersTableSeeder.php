<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\Member;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  $faker = Faker::create();
        // foreach (range(1, 100) as $index) {
        //     DB::table('members')->insert([
        //         'first_name' =>$faker->firstName,
        //         'last_name' => $faker->lastName,    
        //         'email' => $faker->email,
        //         'gender'=> $faker->randomElement(['male','female','other']),
        //         'contact'=> $faker->e164PhoneNumber,
        //         'card_num'=> $faker->numberBetween($min = 4000000000000, $max = 4000999999999) ,
        //         'level_id'=> $faker->randomElement([1,2,3]),
        //         'store_id'=> $faker->randomElement([1,2]),
        //         'points'=> $faker->numberBetween($min = 20, $max = 2000),

        //         ]);
        // }

        // after

        $path = 'database/seeds/s2cSqlSeeders/S2C_members.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('members table seeded!');
    }
}
