<?php

namespace App\Scaffolders;

use Tir\Crud\Support\Scaffold\BaseScaffolder;
use Tir\Crud\Support\Scaffold\Fields\Text;
use Tir\Crud\Support\Scaffold\Fields\TextArea;
use Tir\Crud\Support\Scaffold\Fields\CheckBox;
use App\Models\MinimalExample;

/**
 * MinimalExampleScaffolder - Demonstrating Auto-Fillable Generation
 *
 * This scaffolder shows how the framework automatically generates
 * the $fillable array based on field definitions.
 */
class MinimalExampleScaffolder extends BaseScaffolder
{
    protected function setModel(): string
    {
        return MinimalExample::class;
    }

    protected function setModuleName(): string
    {
        return 'minimal-example';
    }

    protected function setAcl(): bool
    {
        // Enable ACL for this module
        return false;
    }

    public function setFields(): array
    {
        return [
            // These fields will be automatically included in $fillable
            Text::make('title')
                ->display('Title')
                ->rules('required', 'max:255')
                ->fillable(true),

            TextArea::make('description')
                ->display('Description')
                ->rules('nullable')
                ->hideFromIndex(),

            Text::make('slug')
                ->display('Slug')
                ->rules('nullable', 'unique:minimal_examples,slug')
                ->showOnIndex(false),

            // Auto-label example: "user_email" becomes "User Email"
            Text::make('user_email')
                ->rules(['email', 'nullable']),

            // Auto-label example: "created_at" becomes "Created At"
            Text::make('created_at')
                ->onlyOnDetail(),

            // This field will be EXCLUDED from $fillable
            Text::make('internal_notes')
                ->display('Internal Notes')
                ->fillable(false)
                ->onlyOnDetail()
                ->hideFromIndex(),

            // This virtual field will be automatically EXCLUDED
            // Text::make('computed_value')
            //     ->display('Computed Value')
            //     ->virtual(true)
            //     ->hideFromIndex()
            //     ->value(function ($resource) {
            //         return 'Computed: ' . $resource->title;
            //     }),

            CheckBox::make('is_active')
                ->display('Active')
                ->default(true),
        ];
    }

    /**
     * Example of accessing model properties in scaffolder
     * using the new magic method approach
     */
    public function customAction()
    {
        // Both approaches work:

        // Approach 1: Magic method (clean and simple)
        $title = $this->title;
        $isActive = $this->is_active;

        // Approach 2: Helper methods (explicit and safe)
        if ($this->hasValue('title')) {
            $title = $this->getValue('title');
        }

        // Use the values...
        return [
            'title' => $title,
            'is_active' => $isActive,
        ];
    }
}

/**
 * Expected Auto-Generated $fillable Array:
 *
 * Based on the field definitions above, the framework will automatically
 * generate this $fillable array for the MinimalExample model:
 *
 * $fillable = [
 *     'title',        // fillable(true) - included
 *     'description',  // fillable(true) - included
 *     'slug',         // fillable(true) - included
 *     'user_email',   // fillable(true) - included (auto-label: "User Email")
 *     'is_active',    // fillable(true) - included
 *     // 'internal_notes' - EXCLUDED because fillable(false)
 *     // 'computed_value' - EXCLUDED because virtual(true)
 *     // 'created_at' - EXCLUDED because it's a timestamp (auto-managed)
 * ];
 *
 * Auto-Label Examples:
 * - 'user_email' → "User Email" (snake_case converted)
 * - 'created_at' → "Created At" (snake_case converted)
 * - 'is_active' → "Is Active" (snake_case converted)
 * - Custom labels override auto-generation
 *
 * This happens automatically when:
 * 1. The model's $fillable is empty []
 * 2. OR the model doesn't define $fillable at all
 * 3. The framework scans the scaffolder fields
 * 4. Includes fields where fillable !== false AND virtual !== true
 * 5. Auto-generates user-friendly labels from field names
 */
