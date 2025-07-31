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
        // Create admin user first
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Create additional test users
        User::factory(10)->create();

        // Run specific seeders
        $this->call([
            MinimalExampleSeeder::class,
            SampleModelSeeder::class,
        ]);

        $this->command->info('Database seeding completed successfully!');
    }
}
