<?php
use App\Models\cachier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CachiersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $path = 'database/seeds/S2cSqlSeeders/S2C_cachiers.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Cachiers table seeded!');
    }
}
