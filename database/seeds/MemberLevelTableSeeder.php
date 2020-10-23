<?php

use Illuminate\Database\Seeder;
use App\Models\Member_level;
use Illuminate\Support\Facades\DB;

class MemberLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $level = new Member_level();
        // $level->level = 'Bronze';
        // $level->start_point = 0;
        // $level->end_point = 100;
        // $level->store_id = 1;
        // $level->save();
        // $level1 = new Member_level();
        // $level1->level = 'Silver';
        // $level1->start_point = 101;
        // $level1->end_point = 200;
        // $level1->store_id = 1;
        // $level1->save();
        // $level2 = new Member_level();
        // $level2->level = 'gold';
        // $level2->start_point = 201;
        // $level2->end_point = 300;
        // $level2->store_id = 1;
        // $level2->save();


        //AFTER
        $path = 'database/seeds/s2cSqlSeeders/S2C_member_levels.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('member_levels table seeded!');

        
    }
}
