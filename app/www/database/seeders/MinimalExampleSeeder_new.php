<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MinimalExample;
use App\Models\User;

class MinimalExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific examples for demonstration
        $this->createSpecificExamples();

        // Create additional examples using factory
        $this->createFactoryExamples();

        $this->command->info('MinimalExample seeder completed successfully!');
    }

    /**
     * Create specific examples for demonstration purposes
     */
    private function createSpecificExamples(): void
    {
        // Get existing users to attach to examples
        $users = User::take(3)->get();

        if ($users->count() === 0) {
            // Create some users if none exist
            $users = collect([
                User::create([
                    'name' => 'Test User 1',
                    'email' => 'user1@example.com',
                    'password' => bcrypt('password'),
                ]),
                User::create([
                    'name' => 'Test User 2',
                    'email' => 'user2@example.com',
                    'password' => bcrypt('password'),
                ]),
                User::create([
                    'name' => 'Test User 3',
                    'email' => 'user3@example.com',
                    'password' => bcrypt('password'),
                ]),
            ]);
        }

        $examples = [
            [
                'title' => 'First Example',
                'description' => 'This is the first minimal example demonstrating auto-fillable generation.',
                'slug' => 'first-example',
                'is_active' => true,
                'internal_notes' => 'Internal note for first example',
                'users' => [$users[0]->id], // Attach first user
            ],
            [
                'title' => 'Second Example',
                'description' => 'This is the second example showing different field types.',
                'slug' => 'second-example',
                'is_active' => true,
                'internal_notes' => 'Internal note for second example',
                'users' => [$users[1]->id], // Attach second user
            ],
            [
                'title' => 'Inactive Example',
                'description' => 'This example is inactive to demonstrate boolean fields.',
                'slug' => 'inactive-example',
                'is_active' => false,
                'internal_notes' => 'This example is intentionally inactive',
                'users' => [$users[2]->id], // Attach third user
            ],
            [
                'title' => 'Multi-User Example',
                'description' => 'Demonstrates many-to-many relationship with multiple users.',
                'slug' => 'multi-user-demo',
                'is_active' => true,
                'internal_notes' => 'Shows multiple user associations',
                'users' => $users->pluck('id')->toArray(), // Attach all users
            ],
            [
                'title' => 'Auto Label Demo',
                'description' => 'Demonstrates auto-label generation from field names.',
                'slug' => 'auto-label-demo',
                'is_active' => true,
                'internal_notes' => 'Shows auto-label generation features',
                'users' => [$users[0]->id],
            ],
            [
                'title' => 'Virtual Field Test',
                'description' => 'Tests virtual fields and computed values.',
                'slug' => 'virtual-field-test',
                'is_active' => true,
                'internal_notes' => 'Virtual field will show user emails',
                'users' => [$users[1]->id, $users[2]->id], // Attach multiple users
            ],
            [
                'title' => 'Fillable Control Example',
                'description' => 'Demonstrates fillable(false) field behavior.',
                'slug' => 'fillable-control',
                'is_active' => true,
                'internal_notes' => 'This field has fillable(false) so it won\'t be mass assignable',
                'users' => [$users[0]->id],
            ],
        ];

        foreach ($examples as $exampleData) {
            $userIds = $exampleData['users'];
            unset($exampleData['users']); // Remove users from mass assignment data

            $example = MinimalExample::create($exampleData);
            $example->users()->attach($userIds); // Attach users via relationship
        }

        $this->command->info('Created ' . count($examples) . ' specific demonstration examples.');
    }

    /**
     * Create additional examples using factory for bulk data
     */
    private function createFactoryExamples(): void
    {
        // Only create factory examples if we don't have many records already
        $existingCount = MinimalExample::count();

        if ($existingCount < 20) {
            $factoryCount = 15;

            MinimalExample::factory($factoryCount)->create()->each(function ($example) {
                // Attach random users to each factory-created example
                $users = User::inRandomOrder()->take(rand(1, 3))->pluck('id');
                $example->users()->attach($users);
            });

            $this->command->info("Created {$factoryCount} additional examples using factory.");
        } else {
            $this->command->info('Skipped factory examples - sufficient records already exist.');
        }
    }
}
