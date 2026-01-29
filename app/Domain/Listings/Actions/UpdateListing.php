<?php

namespace App\Domain\Listings\Actions;

use App\Models\Listing;
use Illuminate\Support\Str;

class UpdateListing
{
    public function execute(Listing $listing, array $data): Listing
    {
        $storeName = (string) config('app.store.name');
        $storePhone = (string) config('app.store.phone');
        $storeWhatsApp = (string) config('app.store.whatsapp_phone');

        $data['seller_name'] = $storeName;
        $data['seller_phone'] = $storePhone;
        $data['whatsapp_phone'] = $storeWhatsApp;

        if (array_key_exists('title', $data) && $data['title'] !== $listing->title) {
            $data['slug'] = $this->uniqueSlug($data['title'], $listing->id);
        }

        $listing->fill($data);
        $listing->save();

        return $listing;
    }

    private function uniqueSlug(string $title, int $ignoreId): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;

        while (Listing::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}
