<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology',
                'description' => 'Tech-related items and content',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Business',
                'description' => 'Business and enterprise content',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Education',
                'description' => 'Educational materials and resources',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Entertainment',
                'description' => 'Entertainment and media content',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Sports',
                'description' => 'Sports and fitness related content',
                'is_active' => false, // Inactive for testing
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
