<?php

namespace App\Scaffolders;

use App\Models\Category;
use Tir\Crud\Support\Scaffold\BaseScaffolder;
use Tir\Crud\Support\Scaffold\Fields\Text;
use Tir\Crud\Support\Scaffold\Fields\Textarea;
use Tir\Crud\Support\Scaffold\Fields\Toggle;
use Tir\Crud\Support\Scaffold\Fields\Number;

/**
 * CategoryScaffolder - Category CRUD Configuration
 *
 * Demonstrates a simple but complete scaffolder with:
 * - Text field for name (required, unique)
 * - Textarea for description
 * - Toggle for active status
 * - Number field for sort order
 */
class CategoryScaffolder extends BaseScaffolder
{
    /**
     * Set the module name for routing and permissions
     */
    public function setModuleName(): string
    {
        return 'category';
    }

    /**
     * Set the module title for display
     */
    public function setModuleTitle(): string
    {
        return 'Categories';
    }

    /**
     * Set the Eloquent model this scaffolder works with
     */
    protected function setModel(): string
    {
        return Category::class;
    }

    /**
     * Define the fields for CRUD operations
     *
     * This demonstrates a simple but complete scaffolder with:
     * - Required name field with uniqueness constraint
     * - Optional description textarea
     * - Toggle for active status
     * - Sort order number field
     */
    public function setFields(): array
    {
        return [
            Text::make('name')
                ->display('Category Name')
                ->placeholder('Enter category name')
                ->rules(['required', 'string', 'max:255', 'unique:categories,name'])
                ->searchable()
                ->sortable(),

            Textarea::make('description')
                ->display('Description')
                ->placeholder('Optional category description')
                ->rules(['nullable', 'string', 'max:1000'])
                ->hideFromIndex(),

            Toggle::make('is_active')
                ->display('Active')
                ->default(true)
                ->rules(['boolean'])
                ->sortable(),

            Number::make('sort_order')
                ->display('Sort Order')
                ->placeholder('0')
                ->default(0)
                ->rules(['nullable', 'integer', 'min:0'])
                ->hideFromIndex(),
        ];
    }
}
