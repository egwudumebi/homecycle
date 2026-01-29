<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $taxonomy = [
            'Home Appliances' => [
                'Refrigerators',
                'Washing Machines',
                'Microwave Ovens',
                'Air Conditioners',
                'Water Dispensers',
            ],
            'Electronics' => [
                'Televisions',
                'Home Theaters',
                'Sound Systems',
                'Projectors',
                'Streaming Devices',
            ],
            'Kitchen Appliances' => [
                'Blenders',
                'Electric Kettles',
                'Gas Cookers',
                'Air Fryers',
                'Toasters',
            ],
            'Smart Home' => [
                'Smart TVs',
                'Security Cameras',
                'Smart Lighting',
                'Smart Plugs',
                'Video Doorbells',
            ],
        ];

        $categorySort = 1;
        foreach ($taxonomy as $categoryName => $subNames) {
            $category = Category::query()->updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                [
                    'name' => $categoryName,
                    'sort_order' => $categorySort++,
                    'is_active' => true,
                ]
            );

            $subSort = 1;
            foreach ($subNames as $subName) {
                SubCategory::query()->updateOrCreate(
                    ['category_id' => $category->id, 'slug' => Str::slug($subName)],
                    [
                        'name' => $subName,
                        'sort_order' => $subSort++,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
