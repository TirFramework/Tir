<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MinimalExample;

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
        $examples = [
            [
                'title' => 'First Example',
                'description' => 'This is the first minimal example demonstrating auto-fillable generation.',
                'slug' => 'first-example',
                'user_email' => 'user1@example.com',
                'is_active' => true,
                'internal_notes' => 'Internal note for first example',
            ],
            [
                'title' => 'Second Example',
                'description' => 'This is the second example showing different field types.',
                'slug' => 'second-example',
                'user_email' => 'user2@example.com',
                'is_active' => true,
                'internal_notes' => 'Internal note for second example',
            ],
            [
                'title' => 'Inactive Example',
                'description' => 'This example is inactive to demonstrate boolean fields.',
                'slug' => 'inactive-example',
                'user_email' => 'inactive@example.com',
                'is_active' => false,
                'internal_notes' => 'This example is intentionally inactive',
            ],
            [
                'title' => 'Auto Label Demo',
                'description' => 'Demonstrates auto-label generation from field names.',
                'slug' => 'auto-label-demo',
                'user_email' => 'auto@example.com',
                'is_active' => true,
                'internal_notes' => 'Shows user_email auto-label generation',
            ],
            [
                'title' => 'Virtual Field Test',
                'description' => 'Tests virtual fields and computed values.',
                'slug' => 'virtual-field-test',
                'user_email' => 'virtual@example.com',
                'is_active' => true,
                'internal_notes' => 'Virtual field will show: Computed: Virtual Field Test',
            ],
            [
                'title' => 'Fillable Control Example',
                'description' => 'Demonstrates fillable(false) field behavior.',
                'slug' => 'fillable-control',
                'user_email' => 'fillable@example.com',
                'is_active' => true,
                'internal_notes' => 'This field has fillable(false) so it won\'t be mass assignable',
            ],
            [
                'title' => 'Complete Feature Demo',
                'description' => 'Shows all features: auto-fillable, auto-label, virtual fields, and field controls.',
                'slug' => 'complete-demo',
                'user_email' => 'complete@example.com',
                'is_active' => true,
                'internal_notes' => 'Comprehensive example showing all framework features',
            ],
            [
                'title' => 'Long Title to Test Maximum Length Validation',
                'description' => 'This example has a very long title to test the max:255 validation rule that is set in the scaffolder.',
                'slug' => 'long-title-test',
                'user_email' => 'long@example.com',
                'is_active' => false,
                'internal_notes' => 'Tests validation rules and field constraints',
            ],
            [
                'title' => 'Unicode Test Example',
                'description' => 'Tests Unicode support: العربية, 中文, Русский, Ελληνικά',
                'slug' => 'unicode-test',
                'user_email' => 'unicode@example.com',
                'is_active' => true,
                'internal_notes' => 'Tests international character support',
            ],
            [
                'title' => 'Special Characters',
                'description' => 'Tests special characters: !@#$%^&*()_+-=[]{}|;:",.<>?',
                'slug' => 'special-chars',
                'user_email' => 'special@example.com',
                'is_active' => true,
                'internal_notes' => 'Tests handling of special characters in content',
            ],
        ];

        foreach ($examples as $example) {
            MinimalExample::create($example);
        }

        $this->command->info('Created ' . count($examples) . ' specific demonstration examples.');
    }

    /**
     * Create additional examples using factory
     */
    private function createFactoryExamples(): void
    {
        // Create 10 random active examples
        MinimalExample::factory(10)
            ->active()
            ->create();

        // Create 5 inactive examples
        MinimalExample::factory(5)
            ->inactive()
            ->create();

        // Create 3 examples with detailed notes
        MinimalExample::factory(3)
            ->withDetailedNotes()
            ->withDomain('example.org')
            ->create();

        // Create 2 examples with long titles (test validation)
        MinimalExample::factory(2)
            ->longTitle()
            ->create();

        // Create 2 examples with special characters
        MinimalExample::factory(2)
            ->withSpecialChars()
            ->create();

        // Create 3 examples with unicode characters
        MinimalExample::factory(3)
            ->withUnicode()
            ->create();

        $this->command->info('Created 25 additional factory-generated examples.');
    }
}
