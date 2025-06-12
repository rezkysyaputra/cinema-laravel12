<?php

namespace Database\Factories;

use App\Models\Studio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seat>
 */
class SeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'studio_id' => Studio::factory(),
            'seat_number' => $this->faker->numberBetween(1, 10),
            'row_letter' => $this->faker->randomLetter(),
        ];
    }

    /**
     * Create seats for a specific studio based on its capacity
     */
    public function forStudio(Studio $studio): self
    {
        $seats = collect();
        $rows = ceil($studio->capacity / 10); // 10 seats per row

        for ($row = 0; $row < $rows; $row++) {
            $rowLetter = chr(65 + $row); // A, B, C, etc.
            $seatsInRow = min(10, $studio->capacity - ($row * 10)); // Last row might have fewer seats

            for ($seat = 1; $seat <= $seatsInRow; $seat++) {
                $seats->push([
                    'studio_id' => $studio->id,
                    'seat_number' => $seat,
                    'row_letter' => $rowLetter,
                ]);
            }
        }

        return $this->state(function (array $attributes) use ($seats) {
            return $seats->random();
        });
    }
}
