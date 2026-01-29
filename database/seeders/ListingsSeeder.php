<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\LocationCity;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ListingsSeeder extends Seeder
{
    public function run(): void
    {
        $storeName = (string) config('app.store.name');
        $storePhone = (string) config('app.store.phone');
        $storeWhatsApp = (string) config('app.store.whatsapp_phone');

        $subCategories = SubCategory::query()->with('category')->get();
        $cities = LocationCity::query()->with('state')->get();

        $templatesByCategory = [
            'home-appliances' => [
                ['title' => 'Scanfrost 250L Refrigerator', 'min' => 240000, 'max' => 780000],
                ['title' => 'LG 10kg Front Load Washing Machine', 'min' => 220000, 'max' => 680000],
                ['title' => 'Hisense 1.5HP Split Air Conditioner', 'min' => 260000, 'max' => 820000],
                ['title' => 'Haier Thermocool 200L Chest Freezer', 'min' => 180000, 'max' => 520000],
                ['title' => 'Nexus Hot & Cold Water Dispenser', 'min' => 65000, 'max' => 240000],
            ],
            'electronics' => [
                ['title' => 'Samsung 55-Inch Smart 4K TV', 'min' => 280000, 'max' => 950000],
                ['title' => 'LG 65-Inch UHD Smart TV', 'min' => 420000, 'max' => 1300000],
                ['title' => 'Sony 5.1 Channel Home Theater System', 'min' => 120000, 'max' => 650000],
                ['title' => 'JBL Bluetooth Soundbar', 'min' => 85000, 'max' => 550000],
                ['title' => 'Mini HD Projector (1080p)', 'min' => 75000, 'max' => 320000],
            ],
            'kitchen-appliances' => [
                ['title' => 'Binatone 2-Burner Gas Cooker', 'min' => 35000, 'max' => 140000],
                ['title' => 'Nexus 20L Microwave Oven', 'min' => 45000, 'max' => 220000],
                ['title' => 'Kenwood MultiSpeed Blender', 'min' => 22000, 'max' => 110000],
                ['title' => 'Electric Kettle (1.7L, Stainless)', 'min' => 9000, 'max' => 45000],
                ['title' => 'Digital Air Fryer (5L)', 'min' => 38000, 'max' => 190000],
            ],
            'smart-home' => [
                ['title' => 'Mi Smart Security Camera (1080p)', 'min' => 18000, 'max' => 95000],
                ['title' => 'TP-Link Smart Plug (Wi‑Fi)', 'min' => 12000, 'max' => 65000],
                ['title' => 'Smart LED Bulb (RGB, Wi‑Fi)', 'min' => 8000, 'max' => 45000],
                ['title' => 'Smart Video Doorbell (HD)', 'min' => 35000, 'max' => 180000],
                ['title' => 'Smart Indoor Siren Alarm', 'min' => 15000, 'max' => 95000],
            ],
        ];

        $count = 32;

        for ($i = 0; $i < $count; $i++) {
            $sub = $subCategories->random();
            $city = $cities->random();
            $catSlug = $sub->category?->slug;
            $bucket = $templatesByCategory[$catSlug] ?? array_merge(...array_values($templatesByCategory));
            $tpl = $bucket[array_rand($bucket)];

            $title = $tpl['title'];
            $price = random_int((int) $tpl['min'], (int) $tpl['max']);
            $description = "Condition: Brand new / store warranty available.\n\nDetails:\n- Location: {$city->name}, {$city->state->name}\n- Category: {$sub->category->name} / {$sub->name}\n\nDelivery available within the city. Contact our store to confirm stock.";

            $baseSlug = Str::slug($title);
            $slug = $baseSlug.'-'.Str::lower(Str::random(6));
            while (Listing::query()->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.Str::lower(Str::random(6));
            }

            $listing = Listing::query()->create([
                'sub_category_id' => $sub->id,
                'state_id' => $city->state_id,
                'city_id' => $city->id,
                'title' => $title,
                'slug' => $slug,
                'description' => $description,
                'price' => $price,
                'seller_name' => $storeName,
                'seller_phone' => $storePhone,
                'whatsapp_phone' => $storeWhatsApp,
                'status' => 'active',
                'is_featured' => random_int(1, 100) <= 20,
                'published_at' => now()->subDays(random_int(0, 30)),
            ]);

            $imageCount = random_int(2, 4);
            $this->generateListingImages($listing, $title, $imageCount);
        }
    }

    private function generateListingImages(Listing $listing, string $title, int $imageCount): void
    {
        $disk = Storage::disk('public');
        $directory = "listings/{$listing->id}";
        $disk->makeDirectory($directory);

        for ($i = 1; $i <= $imageCount; $i++) {
            $path = sprintf('%s/%02d.jpg', $directory, $i);
            if (!$disk->exists($path)) {
                $disk->put($path, $this->buildListingJpeg($title, $i));
            }

            ListingImage::query()->create([
                'listing_id' => $listing->id,
                'disk' => 'public',
                'path' => $path,
                'sort_order' => $i - 1,
            ]);
        }
    }

    private function buildListingJpeg(string $title, int $variant): string
    {
        if (!function_exists('imagecreatetruecolor')) {
            throw new \RuntimeException('PHP GD extension is required to generate listing images during seeding.');
        }

        $w = 1200;
        $h = 900;
        $img = imagecreatetruecolor($w, $h);

        $hash = md5($title.'|'.$variant);
        $r1 = (int) (hexdec(substr($hash, 0, 2)) * 0.35);
        $g1 = (int) (hexdec(substr($hash, 2, 2)) * 0.35);
        $b1 = (int) (hexdec(substr($hash, 4, 2)) * 0.35);

        $r2 = (int) (hexdec(substr($hash, 6, 2)) * 0.70);
        $g2 = (int) (hexdec(substr($hash, 8, 2)) * 0.70);
        $b2 = (int) (hexdec(substr($hash, 10, 2)) * 0.70);

        $bgColor = imagecolorallocate($img, $r1, $g1, $b1);
        $accentColor = imagecolorallocate($img, $r2, $g2, $b2);
        $white = imagecolorallocate($img, 255, 255, 255);

        imagefilledrectangle($img, 0, 0, $w, $h, $bgColor);
        imagefilledrectangle($img, 0, (int) ($h * 0.72), $w, $h, $accentColor);

        $label = 'HomeCycle';
        imagestring($img, 5, 40, 40, $label, $white);
        imagestring($img, 4, 40, 80, "Photo {$variant}", $white);

        $wrapped = wordwrap($title, 34, "\n", true);
        $lines = explode("\n", $wrapped);
        $y = 170;
        foreach (array_slice($lines, 0, 6) as $line) {
            imagestring($img, 5, 40, $y, $line, $white);
            $y += 30;
        }

        imagestring($img, 3, 40, $h - 60, 'Generated during seeding (local file)', $white);

        ob_start();
        imagejpeg($img, null, 85);
        $jpeg = ob_get_clean();
        imagedestroy($img);

        return is_string($jpeg) ? $jpeg : '';
    }
}
