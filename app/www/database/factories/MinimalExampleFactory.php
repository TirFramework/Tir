<?php

namespace Database\Factories;

use App\Models\MinimalExample;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MinimalExample>
 */
class MinimalExampleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MinimalExample::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(rand(3, 6));

        return [
            'title' => $title,
            'description' => fake()->paragraph(rand(2, 4)),
            'slug' => fake()->unique()->slug(),
            'user_email' => fake()->unique()->safeEmail(),
            'is_active' => fake()->boolean(80), // 80% chance of being active
            'internal_notes' => fake()->optional(0.7)->sentence(), // 70% chance of having notes
        ];
    }

    /**
     * Indicate that the example should be active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the example should be inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the example should have detailed internal notes.
     */
    public function withDetailedNotes(): static
    {
        return $this->state(fn (array $attributes) => [
            'internal_notes' => fake()->paragraphs(2, true),
        ]);
    }

    /**
     * Indicate that the example should have a specific email domain.
     */
    public function withDomain(string $domain): static
    {
        return $this->state(fn (array $attributes) => [
            'user_email' => fake()->userName() . '@' . $domain,
        ]);
    }

    /**
     * Create a long title for testing validation.
     */
    public function longTitle(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => fake()->realText(250), // Close to max length of 255
        ]);
    }

    /**
     * Create an example with special characters.
     */
    public function withSpecialChars(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'Special: ' . fake()->sentence() . ' !@#$%^&*()',
            'description' => 'Description with special chars: ' . fake()->paragraph() . ' []{};:"<>?',
        ]);
    }

    /**
     * Create an example with unicode characters.
     */
    public function withUnicode(): static
    {
        $unicodeTexts = [
            'العربية: ' . fake()->sentence(),
            '中文: ' . fake()->sentence(),
            'Русский: ' . fake()->sentence(),
            'Ελληνικά: ' . fake()->sentence(),
            'हिन्दी: ' . fake()->sentence(),
        ];

        return $this->state(fn (array $attributes) => [
            'title' => fake()->randomElement($unicodeTexts),
            'description' => 'Unicode test: ' . fake()->paragraph(),
        ]);
    }
}
