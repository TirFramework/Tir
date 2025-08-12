<?php

namespace App\Scaffolders;

use Tir\Crud\Support\Scaffold\BaseScaffolder;
use Tir\Crud\Support\Scaffold\Fields\Text;
use Tir\Crud\Support\Scaffold\Fields\TextArea;
use Tir\Crud\Support\Scaffold\Fields\CheckBox;
use Tir\Crud\Support\Scaffold\Fields\Select;
use App\Models\MinimalExample;
use App\Models\User;

/**
 * MinimalExampleScaffolder - Demonstrating Auto-Fillable Generation with Many-to-Many Relationship
 *
 * This scaffolder shows how the framework automatically generates
 * the $fillable array based on field definitions and includes
 * a many-to-many relationship with users.
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
        return true;
    }

    public function setFields(): array
    {
        return [
            // Basic fields that will be automatically included in $fillable
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

            // Many-to-many relationship with users
            Select::make('users')
                ->display('Users')
                ->relation('users', 'email')
                ->data(User::select('id as value', 'email as label')->get()->toArray())
                ->filter()
                ->multiple(true)
                ->rules('required')
                ->searchable(),


            Text::make('created_at')
                ->onlyOnDetail(),

            // This field will be EXCLUDED from $fillable
            Text::make('internal_notes')
                ->display('Internal Notes')
                ->fillable(false)
                ->onlyOnDetail()
                ->hideFromIndex(),

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
 *     'description',  // fillable(true) - included (default)
 *     'slug',         // fillable(true) - included (default)
 *     'users',        // fillable(true) - included (many-to-many relationship)
 *     'is_active',    // fillable(true) - included (default)
 *     // 'internal_notes' - EXCLUDED because fillable(false)
 *     // 'user_emails' - EXCLUDED because virtual(true)
 *     // 'created_at' - EXCLUDED because it's a timestamp (auto-managed)
 * ];
 *
 * Many-to-Many Relationship:
 * - 'users' field creates a many-to-many relationship with User model
 * - Uses the 'users' relationship method on MinimalExample model
 * - Displays user emails in select dropdown
 * - Allows multiple user selection
 * - 'user_emails' virtual field shows selected user emails as comma-separated string
 *
 * Auto-Label Examples:
 * - 'is_active' → "Is Active" (snake_case converted)
 * - 'user_emails' → "User Emails" (snake_case converted)
 * - Custom labels override auto-generation
 *
 * This happens automatically when:
 * 1. The model's $fillable is empty []
 * 2. OR the model doesn't define $fillable at all
 * 3. The framework scans the scaffolder fields
 * 4. Includes fields where fillable !== false AND virtual !== true
 * 5. Auto-generates user-friendly labels from field names
 */
