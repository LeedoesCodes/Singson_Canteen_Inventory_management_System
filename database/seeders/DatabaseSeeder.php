<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // First create users (admin, cashiers, customers)
            UserSeeder::class,
            
            // Then create categories
            CategorySeeder::class,
            
            // Then create products (depends on categories)
            ProductSeeder::class,
            
            // Your existing seeders
            SupplierSeeder::class,
            StockEntrySeeder::class,
            
            // Finally create orders (depends on products and users)
            OrderSeeder::class,
        ]);
    }
}