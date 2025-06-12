<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Seat;
use App\Models\Studio;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create movies
        Movie::factory(20)->create();

        // Create studios with seats
        $studios = Studio::factory(3)->create();
        foreach ($studios as $studio) {
            // Create seats for each studio based on its capacity
            $rows = ceil($studio->capacity / 10);
            for ($row = 0; $row < $rows; $row++) {
                $rowLetter = chr(65 + $row);
                $seatsInRow = min(10, $studio->capacity - ($row * 10));

                for ($seat = 1; $seat <= $seatsInRow; $seat++) {
                    Seat::create([
                        'studio_id' => $studio->id,
                        'seat_number' => $seat,
                        'row_letter' => $rowLetter,
                    ]);
                }
            }
        }

        // Create screenings
        \App\Models\Screening::factory(30)->create();

        // Create users with roles
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        $admin->assignRole('admin');
        $user->assignRole('user');
    }
}
