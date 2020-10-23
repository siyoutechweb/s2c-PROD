<?php

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;
class PaymentMethodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentmethod1 = new PaymentMethod();
        $paymentmethod1->name = 'Cash';
        $paymentmethod1->save();
        $paymentmethod2 = new PaymentMethod();
        $paymentmethod2->name = 'Check';
        $paymentmethod2->save();
        $paymentmethod3 = new PaymentMethod();
        $paymentmethod3->name = 'Credit Card';
        $paymentmethod3->save();
    }
}
