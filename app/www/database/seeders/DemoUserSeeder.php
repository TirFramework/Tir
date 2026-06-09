<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo users with standard password
        $demoPassword = bcrypt('Demo@123456');

        User::firstOrCreate(
            ['email' => 'editor@demo.test'],
            [
                'name' => 'Editor User',
                'email' => 'editor@demo.test',
                'password' => $demoPassword,
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'viewer@demo.test'],
            [
                'name' => 'Viewer User',
                'email' => 'viewer@demo.test',
                'password' => $demoPassword,
                'email_verified_at' => now(),
            ]
        );
    }
}
