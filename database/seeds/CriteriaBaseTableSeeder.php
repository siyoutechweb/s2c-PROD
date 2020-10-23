<?php

use Illuminate\Database\Seeder;
use App\Models\CriteriaBase;
use App\Models\CriteriaUnit;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class CriteriaBaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $path = 'database/seeds/SQLFiles/criteria_base.sql';
            DB::unprepared(file_get_contents($path));
            $this->command->info(' table seeded!');
    }
}
