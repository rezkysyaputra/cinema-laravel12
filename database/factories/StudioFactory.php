<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Studio>
 */
class StudioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));

        // Generate realistic studio capacity (between 50-200 seats)
        $capacity = $this->faker->numberBetween(50, 200);

        return [
            'name' => 'Studio ' . $this->faker->unique()->numberBetween(1, 10),
            'capacity' => $capacity,
        ];
    }
}
