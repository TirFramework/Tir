<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo users first
        $this->call([
            DemoUserSeeder::class,
        ]);

        // Create admin user first (only if it doesn't exist)
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('Admin@123456'),
            ]);
        }

        // Create additional test users (only if we have less than 5 users)
        $userCount = User::count();
        if ($userCount < 5) {
            User::factory(5 - $userCount)->create();
        }

        // Run specific seeders
        $this->call([
            CategorySeeder::class,          // Seed categories first
            MinimalExampleSeeder::class,
            SampleModelSeeder::class,
        ]);

        $this->command->info('Database seeding completed successfully!');
    }
}
