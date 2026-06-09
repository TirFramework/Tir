<?php

namespace App\Scaffolders;

use Tir\Crud\Support\Scaffold\BaseScaffolder;
use Tir\Crud\Support\Scaffold\Fields\Group;
use Tir\Crud\Support\Scaffold\Fields\Slug;
use Tir\Crud\Support\Scaffold\Fields\Text;
use Tir\Crud\Support\Scaffold\Fields\TextArea;
use Tir\Crud\Support\Scaffold\Fields\CheckBox;
use Tir\Crud\Support\Scaffold\Fields\Select;
use Tir\Crud\Support\Scaffold\Actions;
use Tir\Crud\Support\Enums\ActionType;
use App\Models\MinimalExample;
use App\Models\User;
use App\Models\Category;

/**
 * MinimalExampleScaffolder - Demonstrating Various Field Types and Relationships
 *
 * This scaffolder shows how the framework automatically generates
 * the $fillable array based on field definitions and includes:
 * - Multiple select field with array storage (status)
 * - Many-to-many relationships (users and categories)
 * - Various field types and configurations
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
            ActionType::EDIT,
            ActionType::INLINE_EDIT
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

            Group::make('Basic Information')
                ->children(
                    Text::make('categoryName')->display('Category Name')->multiple()->virtual(),

                    // Text::make('title')
                    //     ->options(['prefix' => 'BLA_BLA_'])
                    //     ->display('Title')
                    //     ->searchable()
                    //     ->accessor(function($value) {
                    //         return $value . ' - test';
                    //     })
                    //     ->rules('required', 'max:255'),

                    Text::make('bla_bla')
                        ->display('Bla Bla')
                        ->accessor(function($value, $model) {
                            return $model->title;
                        })->virtual()
                        ->searchQuery(function($query, $req) {
                            return $query->orWhere('title', 'like', "%$req%");
                        })
                        ->appends('title', 'description'),

                    TextArea::make('description')
                        ->display('Description')
                        ->rules('nullable')
                        ->hideFromIndex(),

                    Slug::make('slug')
                        ->display('Slug')
                        ->rules('nullable', 'unique:minimal_examples,slug')
                        ->showOnIndex(false),

                    // Multiple select field with array storage (no relationship)
                    // Select::make('status')
                    //     ->display('Status')
                    //     ->data([
                    //         ['value' => 'draft', 'label' => 'Draft'],
                    //         ['value' => 'pending', 'label' => 'Pending Review'],
                    //         ['value' => 'approved', 'label' => 'Approved'],
                    //         ['value' => 'published', 'label' => 'Published'],
                    //         ['value' => 'archived', 'label' => 'Archived'],
                    //     ])
                    //         ->default('draft')
                    //     ->multiple(true)
                    //     ->filter()
                    //     ->rules('required', 'array')
                    //     ->searchable(),

                    // Many-to-many relationship with users
                    Select::make('users')
                        ->display('Users')
                        ->relation('users','email')
                        ->data(User::select('id as value', 'email as label')->get()->toArray())
                        ->filter()
                        ->multiple(true)
                        ->rules('required'),
                    // ->searchable(),

                    // Many-to-many relationship with categories
                    Select::make('categories')
                        ->display('Categories')
                        ->relation('categories','name')
                        // ->data(Category::where('is_active', true)
                        //     ->select('id as value', 'name as label')
                        //     ->orderBy('sort_order')
                        //     ->get()->toArray())
                        ->filter()
                        ->multiple(true)
                        ->rules('nullable'),
                    // ->searchable(),

                    Select::make('my_author')
                        ->display('Author')
                        ->data(User::select('id as value', 'name as label')->get()->toArray())
                        ->relation('author','name'),

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
                        ->default(true)

                )


        ];
    }


}
