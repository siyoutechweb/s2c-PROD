<?php

use Illuminate\Database\Seeder;

class CriteriaUnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = 'database/seeds/SQLFiles/criteria_units.sql';
            DB::unprepared(file_get_contents($path));
            $this->command->info(' table seeded!');
    }
}
