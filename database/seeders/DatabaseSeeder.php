<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Menu;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'kasir',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
        ]);

        $categories = [
            ['name' => 'Black',        'display_order' => 1, 'status' => 'active'],
            ['name' => 'White',        'display_order' => 2, 'status' => 'active'],
            ['name' => 'Manual Brew',  'display_order' => 3, 'status' => 'active'],
            ['name' => 'Infusion Tea', 'display_order' => 4, 'status' => 'active'],
            ['name' => 'Signature',    'display_order' => 5, 'status' => 'active'],
            ['name' => 'Coffee Milk',  'display_order' => 6, 'status' => 'active'],
            ['name' => 'Milk Base',    'display_order' => 7, 'status' => 'active'],
            ['name' => 'Snack',        'display_order' => 8, 'status' => 'active'],
            ['name' => 'Main Course',  'display_order' => 9, 'status' => 'active'],
        ];

        $categoryIds = [];
        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
            $categoryIds[$categoryData['name']] = $category->id;
        }

        $menus = [
            ['category' => 'Black', 'name' => 'Espresso', 'description' => 'Kopi hitam pekat dengan crema halus.', 'price' => 18000, 'stock' => 20],
            ['category' => 'Black', 'name' => 'Americano', 'description' => 'Kopi hitam dengan tambahan air panas.', 'price' => 20000, 'stock' => 18],
            ['category' => 'White', 'name' => 'Latte', 'description' => 'Espresso dengan susu panas dan busa lembut.', 'price' => 24000, 'stock' => 15],
            ['category' => 'White', 'name' => 'Cappuccino', 'description' => 'Espresso, susu panas, dan busa tebal.', 'price' => 25000, 'stock' => 12],
            ['category' => 'Manual Brew', 'name' => 'Pour Over', 'description' => 'Kopi spesial yang diseduh perlahan.', 'price' => 28000, 'stock' => 10],
            ['category' => 'Infusion Tea', 'name' => 'Orange Tea', 'description' => 'Teh buah citrus segar tanpa kafein.', 'price' => 22000, 'stock' => 10],
            ['category' => 'Signature', 'name' => 'Signature Coffee', 'description' => 'Minuman khusus andalan rumah kopi.', 'price' => 32000, 'stock' => 8],
            ['category' => 'Coffee Milk', 'name' => 'Mocha', 'description' => 'Perpaduan kopi dan cokelat susu.', 'price' => 26000, 'stock' => 14],
            ['category' => 'Milk Base', 'name' => 'Milk Tea', 'description' => 'Teh susu lembut dengan rasa klasik.', 'price' => 22000, 'stock' => 16],
            ['category' => 'Snack', 'name' => 'Croissant', 'description' => 'Roti lapis renyah dengan butter asli.', 'price' => 18000, 'stock' => 12],
            ['category' => 'Main Course', 'name' => 'Chicken Sandwich', 'description' => 'Roti lapis ayam goreng dengan saus spesial.', 'price' => 32000, 'stock' => 10],
        ];

        foreach ($menus as $menuData) {
            Menu::firstOrCreate(
                ['name' => $menuData['name']],
                [
                    'category_id' => $categoryIds[$menuData['category']],
                    'description' => $menuData['description'],
                    'price' => $menuData['price'],
                    'stock' => $menuData['stock'],
                    'is_available' => true,
                ]
            );
        }
    }
}
