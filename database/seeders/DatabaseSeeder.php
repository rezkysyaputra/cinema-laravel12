<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Seat;
use App\Models\Studio;
use App\Models\User;
use Database\Seeders\ScreeningSeeder;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole($adminRole);

        // Create regular user if not exists
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'password' => bcrypt('password'),
            ]
        );
        $user->assignRole($userRole);

        // Create additional users only if total users less than 10
        $userCount = User::count();
        if ($userCount < 10) {
            User::factory(10 - $userCount)->create()->each(function ($user) use ($userRole) {
                $user->assignRole($userRole);
            });
        }

        // Create studios with different configurations
        $studioConfigs = [
            ['name' => 'Studio 1', 'row' => 8, 'column' => 12], // 96 seats
            ['name' => 'Studio 2', 'row' => 6, 'column' => 10], // 60 seats
            ['name' => 'Studio 3', 'row' => 7, 'column' => 8],  // 56 seats
        ];

        foreach ($studioConfigs as $config) {
            Studio::create([
                'name' => $config['name'],
                'row' => $config['row'],
                'column' => $config['column'],
                'capacity' => $config['row'] * $config['column'],
            ]);
        }

        // Seed movies
        $this->call(MovieSeeder::class);

        // Seed screenings
        $this->call(ScreeningSeeder::class);
    }
}
