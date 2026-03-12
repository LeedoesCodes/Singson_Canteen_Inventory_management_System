<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all customers (users with role = 'customer')
        $customers = User::where('role', 'customer')->get();
        if ($customers->isEmpty()) {
            // Create some customers if none exist
            $customers = User::factory(10)->create(['role' => 'customer']);
        }
        
        $products = Product::all();
        $statuses = ['pending', 'preparing', 'ready', 'completed', 'cancelled'];
        
        // Create 200+ orders
        for ($i = 0; $i < 250; $i++) {
            $customer = $customers->random();
            $itemCount = rand(1, 5);
            $totalAmount = 0;
            $orderItems = [];
            
            // Select random products for this order
            $selectedProducts = $products->random(min($itemCount, $products->count()));
            
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->price;
                $subtotal = $price * $quantity;
                $totalAmount += $subtotal;
                
                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Create order with random date in the past 3 months
            $createdAt = $faker->dateTimeBetween('-3 months', 'now');
            
            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => 'ORD-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'total_amount' => $totalAmount,
                'status' => $statuses[array_rand($statuses)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            
            // Create order items
            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }
        }
    }
}