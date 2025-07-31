<?php

namespace App\Scaffolders;

use Tir\Crud\Support\Scaffold\BaseScaffolder;
use Tir\Crud\Facades\Fields;

/**
 * FlexibleFieldsScaffolder - Demonstrates different field access methods
 *
 * This scaffolder shows three different approaches for accessing fields:
 * 1. Direct import (current method)
 * 2. Using Facade
 * 3. Using BaseScaffolder methods (via FieldImports trait)
 */
class FlexibleFieldsScaffolder extends BaseScaffolder
{
    protected function setModel(): string
    {
        return \App\Models\User::class;
    }

    protected function setModuleName(): string
    {
        return 'flexible-fields';
    }

    public function setFields(): array
    {
        return [
            // Method 1: Direct import (current method - requires manual imports)
            \Tir\Crud\Support\Scaffold\Fields\Text::make('method1_direct')
                ->display('Method 1: Direct Import')
                ->rules(['required']),

            // Method 2: Using Facade (clean and readable)
            Fields::text('method2_facade')
                ->display('Method 2: Using Facade')
                ->rules(['required']),

            Fields::password('password_facade')
                ->display('Password (Facade)')
                ->rules(['required', 'min:8']),

            Fields::select('status_facade')
                ->display('Status (Facade)')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive'
                ]),

            // Method 3: Using BaseScaffolder methods (via FieldImports trait)
            $this->text('method3_trait')
                ->display('Method 3: BaseScaffolder Method')
                ->rules(['required']),

            $this->textArea('description_trait')
                ->display('Description (Trait)')
                ->rules(['nullable']),

            $this->checkBox('is_active_trait')
                ->display('Active (Trait)')
                ->default(true),

            $this->datePicker('created_date_trait')
                ->display('Created Date (Trait)')
                ->rules(['nullable', 'date']),

            // Method 4: Hybrid - best of all approaches
            $this->hybridExample(),
        ];
    }

    /**
     * Hybrid example - using the best approach based on conditions
     */
    private function hybridExample(): \Tir\Crud\Support\Scaffold\Fields\Text
    {
        // For simple fields: trait method
        $field = $this->text('hybrid_field')
            ->display('Hybrid Example');

        // For complex logic: Facade
        if ($this->hasValue('is_admin')) {
            $field = Fields::text('admin_field')
                ->display('Admin Field');
        }

        return $field;
    }
}
