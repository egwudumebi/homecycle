<?php

namespace Database\Seeders;

use App\Models\LocationCity;
use App\Models\LocationState;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationsSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            'Lagos' => ['Ikeja', 'Lekki', 'Yaba', 'Surulere', 'Ajah'],
            'Abuja (FCT)' => ['Gwarinpa', 'Wuse', 'Maitama', 'Jabi'],
            'Rivers' => ['Port Harcourt', 'Obio-Akpor'],
            'Oyo' => ['Ibadan', 'Ogbomosho'],
            'Anambra' => ['Awka', 'Onitsha', 'Nnewi'],
        ];

        foreach ($states as $stateName => $cities) {
            $state = LocationState::query()->updateOrCreate(
                ['slug' => Str::slug($stateName)],
                ['name' => $stateName]
            );

            foreach ($cities as $cityName) {
                LocationCity::query()->updateOrCreate(
                    ['state_id' => $state->id, 'slug' => Str::slug($cityName)],
                    ['name' => $cityName]
                );
            }
        }
    }
}
