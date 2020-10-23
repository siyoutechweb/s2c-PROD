<?php
use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Status = new Status();
        $Status->statut_name = 'unchecked';
        $Status->save();
        $Status1 = new Status();
        $Status1->statut_name = 'pending ';
        $Status1->save();
        $Status2 = new Status();
        $Status2->statut_name = 'passed';
        $Status2->save();
        
    }
}
