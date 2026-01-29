<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\LocationCity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition(): array
    {
        $products = [
            ['title' => 'LG 10kg Front Load Washing Machine', 'min' => 220000, 'max' => 680000],
            ['title' => 'Samsung 55-Inch Smart 4K TV', 'min' => 280000, 'max' => 950000],
            ['title' => 'Hisense 1.5HP Split Air Conditioner', 'min' => 260000, 'max' => 820000],
            ['title' => 'Haier Thermocool 200L Chest Freezer', 'min' => 180000, 'max' => 520000],
            ['title' => 'Binatone 2-Burner Gas Cooker', 'min' => 35000, 'max' => 140000],
            ['title' => 'Nexus 20L Microwave Oven', 'min' => 45000, 'max' => 220000],
            ['title' => 'Scanfrost 250L Refrigerator', 'min' => 240000, 'max' => 780000],
            ['title' => 'LG 5.1 Channel Home Theater System', 'min' => 90000, 'max' => 420000],
            ['title' => 'JBL Bluetooth Soundbar', 'min' => 85000, 'max' => 550000],
            ['title' => 'Mi Smart Security Camera (1080p)', 'min' => 18000, 'max' => 95000],
            ['title' => 'TP-Link Smart Plug (Wi‑Fi)', 'min' => 12000, 'max' => 65000],
            ['title' => 'Smart LED Bulb (RGB, Wi‑Fi)', 'min' => 8000, 'max' => 45000],
        ];

        $product = $products[array_rand($products)];
        $title = $product['title'];
        $city = LocationCity::query()->inRandomOrder()->first();

        if (!$city) {
            $city = LocationCityFactory::new()->create();
        }

        return [
            'sub_category_id' => SubCategoryFactory::new(),
            'state_id' => $city->state_id,
            'city_id' => $city->id,
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(6)),
            'description' => "Condition: Brand new / store warranty available.\n\nKey Features:\n- Genuine product\n- Tested and ready for use\n- Delivery available within {$city->name}\n\nContact our store to confirm availability.",
            'price' => fake()->randomFloat(2, (float) $product['min'], (float) $product['max']),
            'seller_name' => (string) config('app.store.name'),
            'seller_phone' => (string) config('app.store.phone'),
            'whatsapp_phone' => (string) config('app.store.whatsapp_phone'),
            'status' => 'active',
            'is_featured' => false,
            'published_at' => now()->subDays(fake()->numberBetween(0, 45)),
        ];
    }
}
