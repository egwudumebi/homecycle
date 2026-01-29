<?php

namespace App\Domain\Listings\Actions;

use App\Models\Listing;
use Illuminate\Support\Str;

class CreateListing
{
    public function execute(array $data): Listing
    {
        $storeName = (string) config('app.store.name');
        $storePhone = (string) config('app.store.phone');
        $storeWhatsApp = (string) config('app.store.whatsapp_phone');

        $data['seller_name'] = $storeName;
        $data['seller_phone'] = $storePhone;
        $data['whatsapp_phone'] = $storeWhatsApp;

        $data['slug'] = $this->uniqueSlug($data['title']);

        if (!array_key_exists('published_at', $data) && (($data['status'] ?? 'active') === 'active')) {
            $data['published_at'] = now();
        }

        return Listing::create($data);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;

        while (Listing::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}
