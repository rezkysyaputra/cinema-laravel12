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
        // Ambil tanggal acak dalam 1 minggu ke depan
        $date = $this->faker->dateTimeBetween('now', '+1 week');
        // Pilih jam bulat antara 10:00 - 22:00
        $hour = $this->faker->numberBetween(10, 22);
        $date->setTime($hour, 0, 0);
        // Pilih harga 25000-45000 kelipatan 5000
        $prices = range(25000, 45000, 5000);
        $price = $this->faker->randomElement($prices);
        return [
            'movie_id' => \App\Models\Movie::get()->random()->id,
            'studio_id' => \App\Models\Studio::get()->random()->id,
            'start_time' => $date,
            'price' => $price,
        ];
    }
}
