<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuCategory;

class MenuCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Appetizers',
                'description' => 'Starters and small dishes to begin your meal',
                'is_active' => true,
            ],
            [
                'name' => 'Main Course',
                'description' => 'Primary dishes and entrees',
                'is_active' => true,
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats to end your meal',
                'is_active' => true,
            ],
            [
                'name' => 'Beverages',
                'description' => 'Drinks, juices, and refreshments',
                'is_active' => true,
            ],
            [
                'name' => 'Salads',
                'description' => 'Fresh and healthy salad options',
                'is_active' => true,
            ],
            [
                'name' => 'Soups',
                'description' => 'Warm and comforting soup varieties',
                'is_active' => true,
            ],
            [
                'name' => 'Pizza',
                'description' => 'Freshly made pizzas with various toppings',
                'is_active' => true,
            ],
            [
                'name' => 'Pasta',
                'description' => 'Italian pasta dishes and noodles',
                'is_active' => true,
            ],
            [
                'name' => 'Grilled',
                'description' => 'Grilled meats and vegetables',
                'is_active' => true,
            ],
            [
                'name' => 'Vegetarian',
                'description' => 'Plant-based and vegetarian options',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            MenuCategory::create($category);
        }
    }
}
