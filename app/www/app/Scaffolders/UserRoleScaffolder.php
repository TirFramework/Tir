<?php

namespace App\Scaffolders;

use App\Models\UserRole;
use App\Services\ModuleService;
use App\Support\Modules\Modules;
use Tir\Crud\Support\Enums\ActionType;
use Tir\Crud\Support\Scaffold\Actions;
use Tir\Crud\Support\Scaffold\BaseScaffolder;
use Tir\Crud\Support\Scaffold\Fields\Text;
use Tir\Crud\Support\Scaffold\Fields\Blank;
use Tir\Crud\Support\Scaffold\Fields\Group;
use Tir\Crud\Support\Scaffold\Fields\Select;
use Tir\Crud\Support\Scaffold\Fields\SwitchBox;

/**
 * UserRoleScaffolder - Role and Permission Management
 *
 * Creates and manages user roles with fine-grained permission controls.
 * Each role can have granular permissions for different modules.
 *
 * On create: Shows basic role information (title, type)
 * On edit: Shows role information + dynamic permission matrix for all modules
 *
 * Example permission structure:
 * {
 *   "user": { "index": true, "create": true, "edit": false },
 *   "category": { "index": true, "show": true, "destroy": false }
 * }
 */
class UserRoleScaffolder extends BaseScaffolder
{
    /**
     * Configure available actions
     */
    protected function setActions(): array
    {
        return Actions::except(
            ActionType::INLINE_EDIT,
            ActionType::RESTORE,
            ActionType::FORCE_DELETE,
        );
    }

    /**
     * Initialize modules when editing (role already exists)
     *
     * On create: role type is null, so we skip module registration
     * On edit: role type is hydrated from DB, so we register modules
     */
    protected function scaffolderInit(): void
    {
        // Only register modules if we're editing an existing role
        if ($this->type === null) {
            return;
        }

        // Register all modules for this role type
        $service = new ModuleService();
        $service->registerModules();
    }

    protected function setModel(): string
    {
        return UserRole::class;
    }

    protected function setModuleName(): string
    {
        return 'userRole';
    }

    protected function setModuleTitle(): string
    {
        return 'User Roles';
    }

    /**
     * Define fields for role CRUD
     *
     * On create: Basic fields (title, type)
     * On edit: Basic fields + Dynamic permission matrix
     */
    public function setFields(): array
    {
        return [
            // Basic Role Information
            Text::make('title')
                ->display('Role Name')
                ->placeholder('e.g., Editor, Moderator')
                ->rules(['required', 'string', 'max:255', 'unique:user_roles,title,' . $this->id]),

            Select::make('type')
                ->display('Role Type')
                ->rules(['required', 'in:admin,partner,employer'])
                ->data(
                    ['label' => 'Admin Panel', 'value' => 'admin'],
                    ['label' => 'Partner Panel', 'value' => 'partner'],
                    ['label' => 'Employer Panel', 'value' => 'employer'],
                )
                ->filter()
                ->hideFromIndex(),

            // Permission Matrix (only shown on edit)
            ...($this->id !== null ? [
                Blank::make('separator')->value('<h3>Module Permissions</h3><hr/>')->hideFromIndex(),
                ...$this->getModulesPermissions(),
            ] : []),
        ];
    }

    /**
     * Get permission fields for all registered modules
     *
     * @return array
     */
    private function getModulesPermissions(): array
    {
        $modules = collect(Modules::list());
        $fields = [];

        // Group permissions by module
        foreach ($modules as $module) {
            $fields[] = Group::make("permission-module-{$module->getName()}")
                ->display('')
                ->children(
                    Blank::make('separator')->value(
                        '<h4 class="bg-gray-100 text-center p-3 mb-2 rounded">'
                            . ucfirst($module->getName())
                            . '</h4>'
                    )->hideFromIndex(),
                    ...$this->getPermissionToggles($module),
                )
                ->col(12)
                ->hideFromIndex();
        }

        return $fields;
    }

    /**
     * Get toggle switches for each permission of a module
     *
     * @param \App\Support\Modules\Module $module
     * @return array
     */
    private function getPermissionToggles($module): array
    {
        $fields = [];

        foreach ($module->getPermissions() as $permission) {
            $fieldName = "permissions.{$module->getName()}.{$permission['value']}";

            $fields[] = SwitchBox::make($fieldName)
                ->display($permission['label'])
                ->data(
                    ['label' => 'Allow', 'value' => true],
                    ['label' => 'Deny', 'value' => false],
                )
                ->default(false)
                ->hideFromIndex();
        }

        return $fields;
    }

    /**
     * Set custom validation messages
     */
    protected function getValidationMessages(): array
    {
        return [
            'title.required' => 'Role name is required.',
            'title.unique' => 'A role with this name already exists.',
            'type.required' => 'Role type must be specified.',
        ];
    }

    /**
     * Set custom module title
     */
    protected function setModuleIcon(): string
    {
        return 'LockOutlined';
    }
}
