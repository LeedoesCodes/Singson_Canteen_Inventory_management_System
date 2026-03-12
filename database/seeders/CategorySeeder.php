<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Meals',
                'description' => 'Rice meals, viands, and complete meals',
                'image' => 'categories/meals.jpg',
            ],
            [
                'name' => 'Snacks',
                'description' => 'Light bites and finger foods',
                'image' => 'categories/snacks.jpg',
            ],
            [
                'name' => 'Beverages',
                'description' => 'Soft drinks, juices, and hot drinks',
                'image' => 'categories/beverages.jpg',
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats and desserts',
                'image' => 'categories/desserts.jpg',
            ],
            [
                'name' => 'Combos',
                'description' => 'Meal deals and combo sets',
                'image' => 'categories/combos.jpg',
            ],
            [
                'name' => 'Rice Bowls',
                'description' => 'Rice topped with various viands',
                'image' => 'categories/rice_bowls.jpg',
            ],
            [
                'name' => 'Noodles',
                'description' => 'Pasta and noodle dishes',
                'image' => 'categories/noodles.jpg',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image'],
                'is_active' => true,
            ]);
        }
    }
}