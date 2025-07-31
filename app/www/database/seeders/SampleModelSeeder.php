<?php

namespace Database\Seeders;

use App\Models\SampleModel;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * SampleModelSeeder - Create sample data for demonstration
 *
 * This seeder creates a comprehensive set of sample data that demonstrates
 * all field types and relationships in the SampleModel.
 */
class SampleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have users to assign as authors
        if (User::count() === 0) {
            User::factory(5)->create();
        }

        // Create various types of sample models
        $this->createFeaturedItems();
        $this->createPublishedItems();
        $this->createDraftItems();
        $this->createPremiumItems();
        $this->createEnterpriseItems();
        $this->createParentChildRelationships();
        $this->createItemsWithFiles();
        $this->createArchivedItems();
    }

    /**
     * Create featured items (high priority, published)
     */
    private function createFeaturedItems(): void
    {
        SampleModel::factory(5)
            ->featured()
            ->withFiles()
            ->create([
                'user_id' => User::inRandomOrder()->first()->id,
            ]);

        $this->command->info('Created 5 featured sample models');
    }

    /**
     * Create regular published items
     */
    private function createPublishedItems(): void
    {
        SampleModel::factory(15)
            ->published()
            ->create([
                'user_id' => User::inRandomOrder()->first()->id,
            ]);

        $this->command->info('Created 15 published sample models');
    }

    /**
     * Create draft items (unpublished)
     */
    private function createDraftItems(): void
    {
        SampleModel::factory(8)
            ->draft()
            ->create([
                'user_id' => User::inRandomOrder()->first()->id,
            ]);

        $this->command->info('Created 8 draft sample models');
    }

    /**
     * Create premium type items
     */
    private function createPremiumItems(): void
    {
        SampleModel::factory(6)
            ->premium()
            ->published()
            ->create([
                'user_id' => User::inRandomOrder()->first()->id,
            ]);

        $this->command->info('Created 6 premium sample models');
    }

    /**
     * Create enterprise type items
     */
    private function createEnterpriseItems(): void
    {
        SampleModel::factory(3)
            ->enterprise()
            ->published()
            ->withFiles()
            ->create([
                'user_id' => User::inRandomOrder()->first()->id,
            ]);

        $this->command->info('Created 3 enterprise sample models');
    }

    /**
     * Create parent-child relationships
     */
    private function createParentChildRelationships(): void
    {
        // Create parent items
        $parents = SampleModel::factory(3)
            ->published()
            ->create([
                'user_id' => User::inRandomOrder()->first()->id,
                'title' => fn() => 'Parent: ' . fake()->sentence(3),
            ]);

        // Create children for each parent
        foreach ($parents as $parent) {
            SampleModel::factory(rand(2, 4))
                ->published()
                ->create([
                    'parent_id' => $parent->id,
                    'user_id' => User::inRandomOrder()->first()->id,
                    'title' => fn() => 'Child of ' . $parent->title . ': ' . fake()->sentence(2),
                ]);
        }

        $this->command->info('Created parent-child relationships');
    }

    /**
     * Create items with file attachments
     */
    private function createItemsWithFiles(): void
    {
        SampleModel::factory(10)
            ->published()
            ->withFiles()
            ->create([
                'user_id' => User::inRandomOrder()->first()->id,
            ]);

        $this->command->info('Created 10 sample models with file attachments');
    }

    /**
     * Create archived items
     */
    private function createArchivedItems(): void
    {
        SampleModel::factory(5)
            ->create([
                'status' => 'archived',
                'is_active' => false,
                'is_published' => false,
                'user_id' => User::inRandomOrder()->first()->id,
            ]);

        $this->command->info('Created 5 archived sample models');
    }

    /**
     * Create specific example items for documentation
     */
    private function createExampleItems(): void
    {
        // Example 1: Complete item with all fields
        SampleModel::create([
            'title' => 'Complete Example with All Fields',
            'slug' => 'complete-example-with-all-fields',
            'description' => 'This is a comprehensive example showing all available field types in the Tir CRUD framework.',
            'content' => 'This sample model demonstrates every field type including text, numbers, dates, files, relationships, and JSON fields. It serves as a reference for developers learning the framework.',
            'email' => 'example@tir-framework.com',
            'website_url' => 'https://tir-framework.com',
            'priority' => 100,
            'price' => 299.99,
            'rating' => 4.8,
            'is_active' => true,
            'is_featured' => true,
            'is_published' => true,
            'publish_date' => now()->subDays(7),
            'start_datetime' => now()->addDays(1),
            'end_datetime' => now()->addDays(30),
            'open_time' => '09:00:00',
            'close_time' => '18:00:00',
            'status' => 'published',
            'type' => 'enterprise',
            'category' => 'Technology',
            'tags' => ['framework', 'laravel', 'crud', 'example'],
            'avatar' => 'examples/avatar-complete.jpg',
            'cover_image' => 'examples/cover-complete.jpg',
            'document_file' => 'examples/documentation.pdf',
            'metadata' => [
                'seo_title' => 'Complete CRUD Example - Tir Framework',
                'seo_description' => 'A comprehensive example of all field types in the Tir CRUD framework.',
                'keywords' => 'tir,framework,crud,laravel,example'
            ],
            'settings' => [
                'allow_comments' => true,
                'send_notifications' => true,
                'visibility' => 'public'
            ],
            'social_links' => [
                'twitter' => 'https://twitter.com/tir-framework',
                'github' => 'https://github.com/tir-framework',
                'linkedin' => 'https://linkedin.com/company/tir-framework'
            ],
            'user_id' => User::first()->id,
        ]);

        // Example 2: Minimal item
        SampleModel::create([
            'title' => 'Minimal Example',
            'slug' => 'minimal-example',
            'status' => 'draft',
            'type' => 'basic',
            'user_id' => User::first()->id,
        ]);

        // Example 3: Premium service
        SampleModel::create([
            'title' => 'Premium Service Package',
            'slug' => 'premium-service-package',
            'description' => 'A premium service offering with advanced features.',
            'price' => 199.99,
            'type' => 'premium',
            'status' => 'published',
            'is_active' => true,
            'is_published' => true,
            'is_featured' => true,
            'priority' => 85,
            'rating' => 4.5,
            'category' => 'Business',
            'tags' => ['premium', 'service', 'business'],
            'user_id' => User::first()->id,
        ]);

        $this->command->info('Created specific example items for documentation');
    }
}
