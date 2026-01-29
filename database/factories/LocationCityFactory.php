<?php

namespace Database\Factories;

use App\Models\LocationCity;
use App\Models\LocationState;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LocationCity>
 */
class LocationCityFactory extends Factory
{
    protected $model = LocationCity::class;

    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'state_id' => LocationStateFactory::new(),
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
