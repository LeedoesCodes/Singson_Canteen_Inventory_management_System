<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create permanent admin account
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        // Create cashier accounts
        User::create([
            'name' => 'Cashier One',
            'email' => 'cashier1@test.com',
            'password' => Hash::make('123456'),
            'role' => 'cashier',
        ]);

        User::create([
            'name' => 'Cashier Two',
            'email' => 'cashier2@test.com',
            'password' => Hash::make('123456'),
            'role' => 'cashier',
        ]);

        // Create sample customers (for testing orders)
        $customers = [
            ['name' => 'John Customer', 'email' => 'john@test.com'],
            ['name' => 'Jane Customer', 'email' => 'jane@test.com'],
            ['name' => 'Bob Customer', 'email' => 'bob@test.com'],
            ['name' => 'Alice Customer', 'email' => 'alice@test.com'],
            ['name' => 'Charlie Customer', 'email' => 'charlie@test.com'],
        ];

        foreach ($customers as $customer) {
            User::create([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'password' => Hash::make('123456'),
                'role' => 'customer',
            ]);
        }

        // Create 15 more random customers using factory
        User::factory(15)->create([
            'role' => 'customer',
            'password' => Hash::make('123456'), // All customers have same easy password for testing
        ]);
    }
}