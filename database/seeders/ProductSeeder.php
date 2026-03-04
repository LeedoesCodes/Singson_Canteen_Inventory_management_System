<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'product_code' => 'P001',
            'product_name' => 'Coca Cola',
            'price' => 20,
            'current_stock' => 50,
        ]);

        Product::create([
            'product_code' => 'P002',
            'product_name' => 'Pepsi',
            'price' => 20,
            'current_stock' => 30,
        ]);

        Product::create([
            'product_code' => 'P003',
            'product_name' => 'Mineral Water',
            'price' => 15,
            'current_stock' => 40,
        ]);
    }
}