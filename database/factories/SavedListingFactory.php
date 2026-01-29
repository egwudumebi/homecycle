<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\SavedListing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SavedListing>
 */
class SavedListingFactory extends Factory
{
    protected $model = SavedListing::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'listing_id' => ListingFactory::new(),
        ];
    }
}
