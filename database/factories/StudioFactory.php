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

    public function configure()
    {
        return $this->afterCreating(function ($studio) {
            $rows = ceil($studio->capacity / 10);
            for ($row = 0; $row < $rows; $row++) {
                $rowLetter = chr(65 + $row);
                $seatsInRow = min(10, $studio->capacity - ($row * 10));
                for ($seat = 1; $seat <= $seatsInRow; $seat++) {
                    \App\Models\Seat::create([
                        'studio_id' => $studio->id,
                        'seat_number' => $seat,
                        'row_letter' => $rowLetter,
                    ]);
                }
            }
        });
    }
}
