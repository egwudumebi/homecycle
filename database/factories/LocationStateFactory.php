<?php

namespace Database\Factories;

use App\Models\LocationState;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LocationState>
 */
class LocationStateFactory extends Factory
{
    protected $model = LocationState::class;

    public function definition(): array
    {
        $name = fake()->unique()->state();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
