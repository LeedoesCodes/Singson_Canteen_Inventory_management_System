<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockEntry;

class StockEntrySeeder extends Seeder
{
    public function run(): void
    {
        StockEntry::create([
            'product_id' => 1,
            'supplier_id' => 1,
            'quantity' => 50,
            'delivery_reference' => 'DR-1001',
        ]);

        StockEntry::create([
            'product_id' => 2,
            'supplier_id' => 2,
            'quantity' => 30,
            'delivery_reference' => 'DR-1002',
        ]);

        StockEntry::create([
            'product_id' => 3,
            'supplier_id' => 3,
            'quantity' => 40,
            'delivery_reference' => 'DR-1003',
        ]);
    }
}