<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MinimalExample;
use App\Models\User;
use App\Models\Category;

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

        // Get existing categories to attach to examples
        $categories = Category::where('is_active', true)->take(4)->get();

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
                'status' => ['published', 'approved'], // Multiple statuses
                'is_active' => true,
                'internal_notes' => 'Internal note for first example',
                'users' => [$users[0]->id], // Attach first user
                'categories' => $categories->count() > 0 ? [$categories[0]->id] : [], // Attach first category
            ],
            [
                'title' => 'Second Example',
                'description' => 'This is the second example showing different field types.',
                'slug' => 'second-example',
                'status' => ['approved'], // Single status in array
                'is_active' => true,
                'internal_notes' => 'Internal note for second example',
                'users' => [$users[1]->id], // Attach second user
                'categories' => $categories->count() > 1 ? [$categories[1]->id] : [], // Attach second category
            ],
            [
                'title' => 'Inactive Example',
                'description' => 'This example is inactive to demonstrate boolean fields.',
                'slug' => 'inactive-example',
                'status' => ['draft'], // Draft status
                'is_active' => false,
                'internal_notes' => 'This example is intentionally inactive',
                'users' => [$users[2]->id], // Attach third user
                'categories' => $categories->count() > 2 ? [$categories[2]->id] : [], // Attach third category
            ],
            [
                'title' => 'Multi-User Example',
                'description' => 'Demonstrates many-to-many relationship with multiple users.',
                'slug' => 'multi-user-demo',
                'status' => ['published', 'approved'], // Multiple statuses
                'is_active' => true,
                'internal_notes' => 'Shows multiple user associations',
                'users' => $users->pluck('id')->toArray(), // Attach all users
                'categories' => $categories->count() > 0 ? $categories->pluck('id')->toArray() : [], // Attach all categories
            ],
            [
                'title' => 'Auto Label Demo',
                'description' => 'Demonstrates auto-label generation from field names.',
                'slug' => 'auto-label-demo',
                'status' => ['pending', 'draft'], // Multiple statuses showing workflow
                'is_active' => true,
                'internal_notes' => 'Shows auto-label generation features',
                'users' => [$users[0]->id],
                'categories' => $categories->count() > 0 ? [$categories[0]->id, $categories[1]->id ?? $categories[0]->id] : [], // Attach first two categories
            ],
            [
                'title' => 'Virtual Field Test',
                'description' => 'Tests virtual fields and computed values.',
                'slug' => 'virtual-field-test',
                'status' => ['approved'], // Single status in array
                'is_active' => true,
                'internal_notes' => 'Virtual field will show user emails',
                'users' => [$users[1]->id, $users[2]->id], // Attach multiple users
                'categories' => $categories->count() > 2 ? [$categories[2]->id] : [], // Attach third category
            ],
            [
                'title' => 'Fillable Control Example',
                'description' => 'Demonstrates fillable(false) field behavior.',
                'slug' => 'fillable-control',
                'status' => ['archived', 'published'], // Multiple statuses - archived but was published
                'is_active' => true,
                'internal_notes' => 'This field has fillable(false) so it won\'t be mass assignable',
                'users' => [$users[0]->id],
                'categories' => $categories->count() > 3 ? [$categories[3]->id] : ($categories->count() > 0 ? [$categories[0]->id] : []), // Attach fourth or first category
            ],
        ];

        foreach ($examples as $exampleData) {
            $userIds = $exampleData['users'];
            $categoryIds = $exampleData['categories'];
            unset($exampleData['users'], $exampleData['categories']); // Remove relationships from mass assignment data

            $example = MinimalExample::create($exampleData);
            $example->users()->attach($userIds); // Attach users via relationship
            if (!empty($categoryIds)) {
                $example->categories()->attach($categoryIds); // Attach categories via relationship
            }
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
                // Update with random status array (1-3 statuses)
                $allStatuses = ['draft', 'pending', 'approved', 'published', 'archived'];
                $statusCount = rand(1, 3); // Random number of statuses
                $randomStatuses = array_slice($allStatuses, 0, $statusCount);
                shuffle($randomStatuses);
                $example->update(['status' => array_slice($randomStatuses, 0, rand(1, count($randomStatuses)))]);

                // Attach random users to each factory-created example
                $users = User::inRandomOrder()->take(rand(1, 3))->pluck('id');
                $example->users()->attach($users);

                // Attach random categories to each factory-created example
                $categories = Category::where('is_active', true)->inRandomOrder()->take(rand(1, 2))->pluck('id');
                if ($categories->count() > 0) {
                    $example->categories()->attach($categories);
                }
            });

            $this->command->info("Created {$factoryCount} additional examples using factory.");
        } else {
            $this->command->info('Skipped factory examples - sufficient records already exist.');
        }
    }
}
