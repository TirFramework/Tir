<?php

namespace App\Scaffolders;

use App\Models\MinimalExample;
use Illuminate\Validation\Rule;
use Tir\Crud\Facades\Fields;
use Tir\Crud\Support\Scaffold\BaseScaffolder;
use Tir\Crud\Support\Scaffold\Fields\Blank;
use Tir\Crud\Support\Scaffold\Fields\CheckBox;
use Tir\Crud\Support\Scaffold\Fields\Password;
use Tir\Crud\Support\Scaffold\Fields\Select;
use Tir\Crud\Support\Scaffold\Fields\Text;

/**
 * UserScaffolder - Example implementation showing best practices
 *
 * This scaffolder demonstrates the clean separation between Model and Scaffolder
 * responsibilities and shows different approaches for accessing model data.
 *
 * @package App\Scaffolders
 */
class UserScaffolder extends BaseScaffolder
{
    /**
     * Define the Eloquent model this scaffolder works with
     *
     * @return string Full class name of the model
     */
    protected function setModel(): string
    {
        return \App\Models\User::class;
    }

    /**
     * Set the module name for routing and permissions
     *
     * @return string Module identifier used in routes and ACL
     */
    public function setModuleName(): string
    {
        return 'user';
    }

    /**
     * Define the fields for CRUD operations
     *
     * This method demonstrates different approaches for conditional field display:
     * 1. Magic method access ($this->property) - Laravel-like, concise
     * 2. Helper methods ($this->hasValue()) - explicit, IDE-friendly
     * 3. Chained methods for field configuration
     *
     * @return array Array of field definitions
     */
    public function setFields(): array
    {
        return [
            // Basic User Information
            Text::make('name')
                ->display('Full Name')
                ->placeholder('Enter user\'s full name')
                ->rules(['required', 'string', 'max:255'])
                ->hideFromIndex(false),

            Fields::text('email')
                ->display('Email Address')
                ->placeholder('user@example.com')
                ->rules(...$this->emailRules())
                ->searchable()
                ->sortable(),

            Fields::select('role_ids')
                ->display('Roles')
                ->relation('roles', 'id', 'title')
                ->data(...$this->getAvailableRoles())
                ->multiple(),


            // Password Section
            Blank::make('separator')->value('<h3>Change Password</h3><hr/>')->hideFromIndex(),

            Password::make('new_password')
                ->display('Password')
                ->creationRules(...$this->passwordRules())
                ->placeholder('Enter password (min 8 chars, letters + numbers + special chars)')
                ->hideFromIndex(),

            CheckBox::make('must_change_password')
                ->display('Must Change Password on Next Login')
                ->hideFromIndex(),
        ];
    }

    /**
     * Email validation rules - handles unique constraint on update
     *
     * Uses Laravel's Rule::unique() with ignore() to exclude the current
     * record when validating during update operations. This prevents the
     * unique constraint from failing when a user keeps their same email.
     *
     * @return array Validation rules
     */
    private function emailRules(): array
    {
        return [
            'required',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($this->id, 'id'),
        ];
    }

    /**
     * Password validation rules - matching CRM security standards
     *
     * Requirements:
     * - Minimum 8 characters, maximum 64
     * - Must contain letters (a-z, A-Z)
     * - Must contain numbers (0-9)
     * - Must contain special characters (!$#%)
     *
     * @return array Validation rules
     */
    private function passwordRules(): array
    {
        return [
            'required',
            'string',
            'min:8',
            'max:64',
            'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
        ];
    }

    /**
     * Set custom validation messages (optional)
     *
     * @return array Custom validation messages
     */
    protected function getValidationMessages(): array
    {
        return [
            'email.unique' => 'This email address is already registered.',
            'password.min' => 'Password must be at least 8 characters long.',
        ];
    }

    /**
     * Set module title for UI display (optional)
     *
     * @return string Human-readable module title
     */
    protected function setModuleTitle(): string
    {
        return 'User Management';
    }


    protected function getAvailableRoles(): array
    {
        return \App\Models\UserRole::select('title', 'id')->get()->map(function ($role) {
            return ['label' => $role->title, 'value' => $role->id];
        })->toArray();
    }

}
