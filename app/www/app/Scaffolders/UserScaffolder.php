<?php

namespace App\Scaffolders;

use Tir\Crud\Support\Scaffold\BaseScaffolder;
use Tir\Crud\Support\Scaffold\Fields\Text;
use Tir\Crud\Support\Scaffold\Fields\Password;
use Tir\Crud\Facades\Fields;

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
            // Example 1: Classic method (direct import) - still works
            Text::make('name')
                ->display('Full Name')
                ->placeholder('Enter user\'s full name')
                ->rules(['required', 'string', 'max:255'])
                ->hideFromAll()
                ->showOnEditing(isset($this->name)),

            // Example 2: Facade method - namespace-change resistant
            Fields::text('email')
                ->display('Email Address')
                ->placeholder('user@example.com')
                ->rules(['required', 'email', 'max:255', 'unique:users,email'])
                ->showOnEditing($this->hasValue('email'))
                ->searchable()
                ->sortable(),

            // Example 3: BaseScaffolder method - simple and clean
            $this->password('password')
                ->display('Password')
                ->placeholder('Enter secure password')
                ->rules(['required', 'min:8'])
                ->hideFromIndex()
                ->hideFromDetail(),

            // Example 4: Hybrid method based on conditions
            $this->conditionalField(),
        ];
    }

    /**
     * Conditional field example using different methods
     */
    private function conditionalField()
    {
        // Default: trait method (simple)
        $field = $this->text('status')
            ->display('User Status')
            ->default('active');

        // If admin: use Facade (more complex)
        if ($this->hasValue('role') && $this->getValue('role') === 'admin') {
            $field = Fields::select('status')
                ->display('Admin Status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'suspended' => 'Suspended',
                    'super_admin' => 'Super Admin',
                ]);
        }

        return $field;
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

    /**
     * Configure available actions (optional)
     *
     * @return array Actions configuration
     */
    protected function setActions(): array
    {
        return [
            'create' => true,   // Allow creating new users
            'edit' => true,     // Allow editing users
            'show' => true,     // Allow viewing user details
            'destroy' => false, // Disable user deletion for safety
        ];
    }
}
