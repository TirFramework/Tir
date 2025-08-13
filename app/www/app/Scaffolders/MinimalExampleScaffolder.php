<?php

namespace App\Scaffolders;

use Tir\Crud\Support\Scaffold\BaseScaffolder;
use Tir\Crud\Support\Scaffold\Fields\Text;
use Tir\Crud\Support\Scaffold\Fields\TextArea;
use Tir\Crud\Support\Scaffold\Fields\CheckBox;
use Tir\Crud\Support\Scaffold\Fields\Select;
use Tir\Crud\Support\Scaffold\Actions;
use Tir\Crud\Support\Scaffold\ActionType;
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

    /**
     * ✨ NEW: Configure actions using type-safe ActionType enum
     *
     * @return array<string, bool>
     */
    protected function setActions(): array
    {
        // ✨ Mix core enum actions with custom actions for maximum flexibility!
        return Actions::only(
            ActionType::INDEX,
            ActionType::CREATE,
            ActionType::SHOW,
            'inline-edit',          // ✅ Custom action for inline editing
            'bulk-export',          // ✅ Custom action for bulk operations
            'send-notification'     // ✅ Custom action for notifications
        );

        // Alternative approaches:

        // Start with basic actions and add custom ones:
        // return Actions::addCustom(
        //     Actions::basic(),
        //     'inline-edit',
        //     'bulk-operations',
        //     'custom-workflow'
        // );

        // Use the mixed() method for cleaner syntax:
        // return Actions::mixed(
        //     [ActionType::INDEX, ActionType::EDIT, ActionType::SHOW],
        //     ['inline-edit', 'quick-duplicate', 'export-pdf']
        // );

        // Or exclude specific actions:
        // return Actions::except(
        //     ActionType::FORCE_DELETE,  // No permanent deletion
        //     ActionType::DESTROY,       // No soft delete
        // );

        // Other predefined options:
        // return Actions::all();                    // All core actions enabled
        // return Actions::basic();                  // INDEX, CREATE, SHOW, EDIT only
        // return Actions::readOnly();               // INDEX, SHOW only
    }

    public function setFields(): array
    {
        return [
            // Basic fields that will be automatically included in $fillable
            Text::make('title')
                ->display('Title')
                ->rules('required', 'max:255'),

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


}
