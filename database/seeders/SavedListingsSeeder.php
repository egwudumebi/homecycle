<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\SavedListing;
use App\Models\User;
use Illuminate\Database\Seeder;

class SavedListingsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->where('role', 'user')->get();
        $listings = Listing::query()->get();

        if ($users->isEmpty() || $listings->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            $take = random_int(2, 8);
            $picked = $listings->shuffle()->take($take);

            foreach ($picked as $listing) {
                SavedListing::query()->firstOrCreate([
                    'user_id' => $user->id,
                    'listing_id' => $listing->id,
                ]);
            }
        }
    }
}
