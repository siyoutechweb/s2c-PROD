<?php

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = 'database/seeds/SQLFiles/categories.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Categories table seeded!');

    }
}
