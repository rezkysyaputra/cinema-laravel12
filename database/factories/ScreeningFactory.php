<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Screening>
 */
class ScreeningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movie_id' => \App\Models\Movie::get()->random()->id,
            'studio_id' => \App\Models\Studio::get()->random()->id,
            'start_time' => $this->faker->dateTimeBetween('now', '+1 week'),
            'price' => $this->faker->numberBetween(30000, 100000),
        ];
    }
}
