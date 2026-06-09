<?php

namespace App\Services;

use App\Support\Modules\Module;
use App\Support\Modules\Modules;

/**
 * ModuleService - Registers all available modules and their permissions
 *
 * This service defines what modules exist in the system and what
 * actions (permissions) are available for each module.
 *
 * Permissions are defined in terms of CRUD operations:
 * - index: List all records
 * - show: View record details
 * - create: Create new record
 * - edit: Update existing record
 * - destroy: Delete record
 */
class ModuleService
{
    /**
     * Register all available modules
     *
     * Called during application bootstrap to populate the module registry.
     */
    public function registerModules(): void
    {
        // User Management
        $this->registerModule('user', $this->crudPermissions());
        $this->registerModule('userRole', $this->crudPermissions());

        // Demo CRUD Modules
        $this->registerModule('category', $this->crudPermissions());
        $this->registerModule('sample-model', $this->crudPermissions());
        $this->registerModule('minimal-example', $this->crudPermissions());
        $this->registerModule('flexible-fields', $this->crudPermissions());
    }

    /**
     * Register a single module with its permissions
     *
     * @param string $name Module name
     * @param array $permissions Array of permission arrays
     */
    private function registerModule(string $name, array $permissions): void
    {
        $module = new Module($name);
        $module->setPermissions($permissions);
        Modules::register($module);
    }

    /**
     * Get standard CRUD permissions
     *
     * Returns: index, show, create, edit, destroy
     *
     * @return array
     */
    private function crudPermissions(): array
    {
        return [
            ['label' => 'List', 'value' => 'index'],
            ['label' => 'View', 'value' => 'show'],
            ['label' => 'Create', 'value' => 'create'],
            ['label' => 'Edit', 'value' => 'edit'],
            ['label' => 'Delete', 'value' => 'destroy'],
        ];
    }

    /**
     * Get read-only permissions (index and show only)
     *
     * @return array
     */
    private function readOnlyPermissions(): array
    {
        return [
            ['label' => 'List', 'value' => 'index'],
            ['label' => 'View', 'value' => 'show'],
        ];
    }

    /**
     * Get custom permissions
     *
     * @param array $actions Array of action names/labels
     * @return array
     */
    private function customPermissions(array $actions): array
    {
        $permissions = [];
        foreach ($actions as $label => $value) {
            $permissions[] = [
                'label' => is_numeric($label) ? $value : $label,
                'value' => is_numeric($label) ? $value : $value,
            ];
        }
        return $permissions;
    }
}
