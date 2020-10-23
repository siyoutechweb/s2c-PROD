<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(RolesTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(ShopsTableSeeder::class);
         $this->call(ChainsTableSeeder::class);
         $this->call(CategoriesTableSeeder::class);
         $this->call(MemberLevelTableSeeder::class);
         $this->call(MembersTableSeeder::class);
         $this->call(DiscountTypeTableSeeder::class);
         $this->call(ProductsTableSeeder::class);
         $this->call(PaymentMethodeTableSeeder::class);
        //  $this->call(ProductItemTableSeeder::class);
          
        //  $this->call(CriteriaUnitsTableSeeder::class);
        //  $this->call(CriteriaCategoryTableSeeder::class);
        //  $this->call(ItemCriteriaTableSeeder::class);
        //  $this->call(ProductBrandTableSeeder::class);
         $this->call(SupplierTableSeeder::class);
         $this->call(StatusTableSeeder::class);
         $this->call(CachiersTableSeeder::class);
        // $this->call(CriteriaBaseTableSeeder::class);
        $this->call(ProductBaseTableSeeder::class);
         
         
         
    }
}
