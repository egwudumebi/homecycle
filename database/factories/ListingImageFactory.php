<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ListingImage>
 */
class ListingImageFactory extends Factory
{
    protected $model = ListingImage::class;

    public function definition(): array
    {
        return [
            'listing_id' => ListingFactory::new(),
            'disk' => 'public',
            'path' => 'listings/sample-1.jpg',
            'sort_order' => 0,
        ];
    }
}
