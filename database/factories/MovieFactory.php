<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));

        return [
            'title' => $faker->movie(),
            'synopsis' => $faker->overview(),
            'duration' => $this->faker->numberBetween(80, 180),
            'genre' => $faker->movieGenre(),
            'poster_path' => null,
            'trailer_url' => $faker->url(),
            'release_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
        ];
    }
}
