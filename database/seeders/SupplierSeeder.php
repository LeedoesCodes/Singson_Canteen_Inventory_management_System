<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::create([
            'supplier_code' => 'S001',
            'supplier_name' => 'Coca Supplier Inc.',
            'contact_email' => 'coca@supplier.com',
            'contact_number' => '09123456789',
        ]);

        Supplier::create([
            'supplier_code' => 'S002',
            'supplier_name' => 'Pepsi Distributor',
            'contact_email' => 'pepsi@supplier.com',
            'contact_number' => '09198765432',
        ]);

        Supplier::create([
            'supplier_code' => 'S003',
            'supplier_name' => 'Water Supply Co.',
            'contact_email' => 'water@supplier.com',
            'contact_number' => '09223334444',
        ]);
    }
}