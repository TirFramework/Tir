<?php

namespace Database\Factories;

use App\Models\SampleModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SampleModel>
 */
class SampleModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SampleModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(rand(3, 8));

        return [
            // Text fields
            'title' => $title,
            'slug' => \Str::slug($title),
            'description' => $this->faker->paragraph(2),
            'content' => $this->faker->paragraphs(rand(3, 6), true),

            // Contact fields
            'email' => $this->faker->email(),
            'website_url' => $this->faker->url(),

            // Number fields
            'priority' => $this->faker->numberBetween(0, 100),
            'price' => $this->faker->randomFloat(2, 0, 9999.99),
            'rating' => $this->faker->randomFloat(2, 0, 5),

            // Boolean fields
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'is_published' => $this->faker->boolean(70), // 70% chance of being published

            // Date fields
            'publish_date' => $this->faker->dateTimeBetween('-1 year', '+6 months'),
            'start_datetime' => $this->faker->dateTimeBetween('-1 month', '+3 months'),
            'end_datetime' => $this->faker->dateTimeBetween('+3 months', '+6 months'),
            'open_time' => $this->faker->time('H:i'),
            'close_time' => $this->faker->time('H:i'),

            // Select fields
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'type' => $this->faker->randomElement(['basic', 'premium', 'enterprise']),
            'category' => $this->faker->randomElement([
                'Technology', 'Business', 'Education', 'Health', 'Entertainment',
                'Sports', 'Travel', 'Food', 'Fashion', 'Science'
            ]),

            // Array/JSON fields
            'tags' => $this->faker->words(rand(2, 6)),
            'gallery' => $this->generateGalleryData(),
            'metadata' => $this->generateMetadata(),
            'settings' => $this->generateSettings(),
            'social_links' => $this->generateSocialLinks(),

            // Foreign keys
            'user_id' => User::factory(),
            'parent_id' => null, // Will be set in specific states

            // Timestamps
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    /**
     * Generate sample gallery data
     */
    private function generateGalleryData(): array
    {
        $count = $this->faker->numberBetween(0, 5);
        $gallery = [];

        for ($i = 0; $i < $count; $i++) {
            $gallery[] = [
                'url' => $this->faker->imageUrl(800, 600, 'business'),
                'alt' => $this->faker->sentence(3),
                'caption' => $this->faker->sentence(6),
            ];
        }

        return $gallery;
    }

    /**
     * Generate sample metadata
     */
    private function generateMetadata(): array
    {
        return [
            'seo_title' => $this->faker->sentence(rand(3, 8)),
            'seo_description' => $this->faker->paragraph(1),
            'keywords' => implode(',', $this->faker->words(rand(3, 8))),
            'canonical_url' => $this->faker->url(),
            'robots' => $this->faker->randomElement(['index,follow', 'noindex,follow', 'index,nofollow']),
        ];
    }

    /**
     * Generate sample settings
     */
    private function generateSettings(): array
    {
        return [
            'allow_comments' => $this->faker->boolean(80),
            'send_notifications' => $this->faker->boolean(40),
            'visibility' => $this->faker->randomElement(['public', 'private', 'restricted']),
            'auto_publish' => $this->faker->boolean(30),
            'expire_date' => $this->faker->optional(0.3)->dateTimeBetween('+1 month', '+1 year'),
        ];
    }

    /**
     * Generate sample social links
     */
    private function generateSocialLinks(): array
    {
        $platforms = ['facebook', 'twitter', 'linkedin', 'instagram', 'youtube'];
        $links = [];

        foreach ($platforms as $platform) {
            if ($this->faker->boolean(60)) { // 60% chance for each platform
                $links[$platform] = $this->faker->url();
            }
        }

        return $links;
    }

    /**
     * State for published items
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'is_active' => true,
            'status' => 'published',
            'publish_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * State for featured items
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'is_published' => true,
            'is_active' => true,
            'status' => 'published',
            'priority' => $this->faker->numberBetween(80, 100),
        ]);
    }

    /**
     * State for draft items
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'status' => 'draft',
            'publish_date' => null,
        ]);
    }

    /**
     * State for premium type
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'premium',
            'price' => $this->faker->randomFloat(2, 100, 999.99),
            'priority' => $this->faker->numberBetween(50, 80),
        ]);
    }

    /**
     * State for enterprise type
     */
    public function enterprise(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'enterprise',
            'price' => $this->faker->randomFloat(2, 1000, 9999.99),
            'priority' => $this->faker->numberBetween(70, 100),
            'is_featured' => true,
        ]);
    }

    /**
     * State for items with parent (child items)
     */
    public function withParent(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => SampleModel::factory(),
        ]);
    }

    /**
     * State for items with files
     */
    public function withFiles(): static
    {
        return $this->state(fn (array $attributes) => [
            'avatar' => 'sample-images/avatar-' . $this->faker->numberBetween(1, 10) . '.jpg',
            'cover_image' => 'sample-images/cover-' . $this->faker->numberBetween(1, 20) . '.jpg',
            'document_file' => 'sample-documents/doc-' . $this->faker->numberBetween(1, 5) . '.pdf',
        ]);
    }
}
