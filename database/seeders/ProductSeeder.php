<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get category IDs
        $categories = Category::all()->keyBy('name');
        
        $products = [
            // Meals (Category ID: 1)
            [
                'category' => 'Meals',
                'items' => [
                    ['name' => 'Chicken Adobo', 'price' => 65, 'code' => 'ML001', 'desc' => 'Tender chicken simmered in soy sauce and vinegar'],
                    ['name' => 'Pork Sinigang', 'price' => 70, 'code' => 'ML002', 'desc' => 'Sour soup with pork and vegetables'],
                    ['name' => 'Beef Steak', 'price' => 85, 'code' => 'ML003', 'desc' => 'Beef slices in soy sauce and onion rings'],
                    ['name' => 'Fried Chicken', 'price' => 60, 'code' => 'ML004', 'desc' => 'Crispy fried chicken'],
                    ['name' => 'Fish Fillet', 'price' => 55, 'code' => 'ML005', 'desc' => 'Breaded fish fillet with tartar sauce'],
                    ['name' => 'Menudo', 'price' => 60, 'code' => 'ML006', 'desc' => 'Pork stew with vegetables'],
                ]
            ],
            
            // Snacks (Category ID: 2)
            [
                'category' => 'Snacks',
                'items' => [
                    ['name' => 'French Fries', 'price' => 45, 'code' => 'SN001', 'desc' => 'Crispy golden fries'],
                    ['name' => 'Burger', 'price' => 55, 'code' => 'SN002', 'desc' => 'Beef patty with lettuce and tomato'],
                    ['name' => 'Cheese Burger', 'price' => 60, 'code' => 'SN003', 'desc' => 'Burger with cheese slice'],
                    ['name' => 'Chicken Sandwich', 'price' => 50, 'code' => 'SN004', 'desc' => 'Grilled chicken sandwich'],
                    ['name' => 'Fish Sandwich', 'price' => 48, 'code' => 'SN005', 'desc' => 'Breaded fish sandwich'],
                    ['name' => 'Hotdog', 'price' => 35, 'code' => 'SN006', 'desc' => 'Beef hotdog with bun'],
                ]
            ],
            
            // Beverages (Category ID: 3)
            [
                'category' => 'Beverages',
                'items' => [
                    ['name' => 'Coca Cola', 'price' => 20, 'code' => 'BV001', 'desc' => 'Ice cold soda'],
                    ['name' => 'Pepsi', 'price' => 20, 'code' => 'BV002', 'desc' => 'Ice cold pepsi'],
                    ['name' => 'Mineral Water', 'price' => 15, 'code' => 'BV003', 'desc' => '500ml bottled water'],
                    ['name' => 'Iced Tea', 'price' => 25, 'code' => 'BV004', 'desc' => 'Sweetened iced tea'],
                    ['name' => 'Coffee', 'price' => 30, 'code' => 'BV005', 'desc' => 'Hot brewed coffee'],
                    ['name' => 'Orange Juice', 'price' => 35, 'code' => 'BV006', 'desc' => 'Fresh orange juice'],
                    ['name' => 'Lemonade', 'price' => 30, 'code' => 'BV007', 'desc' => 'Fresh lemonade'],
                    ['name' => 'Milk Tea', 'price' => 45, 'code' => 'BV008', 'desc' => 'Classic milk tea'],
                ]
            ],
            
            // Desserts (Category ID: 4)
            [
                'category' => 'Desserts',
                'items' => [
                    ['name' => 'Ice Cream', 'price' => 30, 'code' => 'DS001', 'desc' => 'Vanilla ice cream scoop'],
                    ['name' => 'Chocolate Cake', 'price' => 50, 'code' => 'DS002', 'desc' => 'Slice of chocolate cake'],
                    ['name' => 'Leche Flan', 'price' => 40, 'code' => 'DS003', 'desc' => 'Creamy caramel custard'],
                    ['name' => 'Buko Salad', 'price' => 35, 'code' => 'DS004', 'desc' => 'Young coconut salad'],
                    ['name' => 'Mango Float', 'price' => 45, 'code' => 'DS005', 'desc' => 'Mango cream dessert'],
                ]
            ],
            
            // Combos (Category ID: 5)
            [
                'category' => 'Combos',
                'items' => [
                    ['name' => 'Chicken Joy Combo', 'price' => 99, 'code' => 'CB001', 'desc' => 'Fried chicken with rice and drink'],
                    ['name' => 'Burger Steak Combo', 'price' => 89, 'code' => 'CB002', 'desc' => 'Burger steak with rice and drink'],
                    ['name' => 'Chicken Sandwich Combo', 'price' => 85, 'code' => 'CB003', 'desc' => 'Chicken sandwich with fries and drink'],
                    ['name' => 'Burger Fries Combo', 'price' => 79, 'code' => 'CB004', 'desc' => 'Burger with fries and drink'],
                ]
            ],
            
            // Rice Bowls (Category ID: 6)
            [
                'category' => 'Rice Bowls',
                'items' => [
                    ['name' => 'Beef Bowl', 'price' => 75, 'code' => 'RB001', 'desc' => 'Beef slices over rice'],
                    ['name' => 'Chicken Bowl', 'price' => 65, 'code' => 'RB002', 'desc' => 'Grilled chicken over rice'],
                    ['name' => 'Pork Bowl', 'price' => 70, 'code' => 'RB003', 'desc' => 'Pork adobo over rice'],
                    ['name' => 'Tofu Bowl', 'price' => 55, 'code' => 'RB004', 'desc' => 'Fried tofu with vegetables over rice'],
                ]
            ],
            
            // Noodles (Category ID: 7)
            [
                'category' => 'Noodles',
                'items' => [
                    ['name' => 'Pancit Canton', 'price' => 50, 'code' => 'ND001', 'desc' => 'Stir-fried noodles'],
                    ['name' => 'Spaghetti', 'price' => 55, 'code' => 'ND002', 'desc' => 'Meat sauce spaghetti'],
                    ['name' => 'Carbonara', 'price' => 60, 'code' => 'ND003', 'desc' => 'Creamy bacon pasta'],
                    ['name' => 'Lasagna', 'price' => 75, 'code' => 'ND004', 'desc' => 'Baked lasagna'],
                ]
            ],
        ];

        $stock = [30, 45, 60, 25, 40, 35, 50, 55]; // Various stock quantities

        foreach ($products as $categoryGroup) {
            $category = $categories[$categoryGroup['category']];
            
            foreach ($categoryGroup['items'] as $item) {
                Product::create([
                    'product_code' => $item['code'],
                    'product_name' => $item['name'],
                    'description' => $item['desc'],
                    'price' => $item['price'],
                    'current_stock' => $stock[array_rand($stock)],
                    'is_available' => true,
                    'category_id' => $category->id,
                    'image' => 'products/' . strtolower(str_replace(' ', '_', $item['name'])) . '.jpg',
                ]);
            }
        }
    }
}