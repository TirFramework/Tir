<?php

namespace Tir\Crud\Support\Scaffold;

/**
 * Actions Configuration Manager
 *
 * Provides utilities for managing CRUD actions with type safety through ActionType enum.
 * Supports both modern enum-based approach and legacy string-based approach for backward compatibility.
 *
 * @example Actions::all()                              // Get all default actions
 * @example Actions::only(ActionType::INDEX, ActionType::SHOW)   // Enable only specific actions
 * @example Actions::except(ActionType::DESTROY)       // Enable all except specific actions
 * @example Actions::none()                             // Disable all actions
 */
class Actions
{
    /**
     * Default action states - all enabled by default
     */
    private static function getDefaults(): array
    {
        return [
            ActionType::INDEX->value => true,
            ActionType::CREATE->value => true,
            ActionType::SHOW->value => true,
            ActionType::EDIT->value => true,
            ActionType::DESTROY->value => true,
            ActionType::FORCE_DELETE->value => true,
            ActionType::RESTORE->value => true,
        ];
    }

    /**
     * Get all actions enabled (default configuration)
     *
     * @return array<string, bool>
     */
    public static function all(): array
    {
        return self::getDefaults();
    }

    /**
     * Get all actions disabled
     *
     * @return array<string, bool>
     */
    public static function none(): array
    {
        $result = [];
        foreach (ActionType::cases() as $action) {
            $result[$action->value] = false;
        }
        return $result;
    }

    /**
     * Enable only specific actions (supports both enum and custom string actions)
     *
     * @param ActionType|string ...$actions Actions to enable
     * @return array<string, bool>
     *
     * @example Actions::only(ActionType::INDEX, ActionType::SHOW)          // Type-safe enum actions
     * @example Actions::only(ActionType::INDEX, 'inline-edit', 'bulk-export') // Mixed: enum + custom
     * @example Actions::only('custom-action1', 'custom-action2')           // Pure custom actions
     */
    public static function only(ActionType|string ...$actions): array
    {
        $result = self::none(); // Start with all disabled

        foreach ($actions as $action) {
            $actionKey = $action instanceof ActionType ? $action->value : $action;
            $result[$actionKey] = true;
        }

        return $result;
    }

    /**
     * Enable all actions except specific ones (supports both enum and custom string actions)
     *
     * @param ActionType|string ...$actions Actions to disable
     * @return array<string, bool>
     *
     * @example Actions::except(ActionType::DESTROY, ActionType::FORCE_DELETE) // Disable enum actions
     * @example Actions::except('dangerous-action', ActionType::DESTROY)       // Mixed: custom + enum
     */
    public static function except(ActionType|string ...$actions): array
    {
        $result = self::all(); // Start with all enabled

        foreach ($actions as $action) {
            $actionKey = $action instanceof ActionType ? $action->value : $action;
            $result[$actionKey] = false;
        }

        return $result;
    }

    /**
     * Add custom actions to an existing configuration
     *
     * @param array<string, bool> $baseConfig Base configuration
     * @param string ...$customActions Custom action names to add
     * @return array<string, bool>
     *
     * @example Actions::addCustom(Actions::basic(), 'inline-edit', 'bulk-export')
     */
    public static function addCustom(array $baseConfig, string ...$customActions): array
    {
        $result = $baseConfig;

        foreach ($customActions as $action) {
            $result[$action] = true;
        }

        return $result;
    }

    /**
     * Create a configuration with both enum and custom actions
     *
     * @param array $enumActions Array of ActionType enum cases
     * @param array $customActions Array of custom action strings
     * @return array<string, bool>
     *
     * @example Actions::mixed([ActionType::INDEX, ActionType::EDIT], ['inline-edit', 'bulk-export'])
     */
    public static function mixed(array $enumActions, array $customActions = []): array
    {
        $result = self::none();

        // Add enum actions
        foreach ($enumActions as $action) {
            if ($action instanceof ActionType) {
                $result[$action->value] = true;
            }
        }

        // Add custom actions
        foreach ($customActions as $action) {
            if (is_string($action)) {
                $result[$action] = true;
            }
        }

        return $result;
    }    /**
     * Get basic CRUD actions (no soft delete support required)
     *
     * @return array<string, bool>
     */
    public static function basic(): array
    {
        return self::only(
            ActionType::INDEX,
            ActionType::CREATE,
            ActionType::SHOW,
            ActionType::EDIT
        );
    }

    /**
     * Get read-only actions (index and show only)
     *
     * @return array<string, bool>
     */
    public static function readOnly(): array
    {
        return self::only(ActionType::INDEX, ActionType::SHOW);
    }

    /**
     * Get actions for models with soft delete support
     *
     * @return array<string, bool>
     */
    public static function withSoftDeletes(): array
    {
        return self::all(); // All actions including restore
    }

    /**
     * Get actions for models without soft delete support
     *
     * @return array<string, bool>
     */
    public static function withoutSoftDeletes(): array
    {
        return self::except(ActionType::RESTORE, ActionType::FORCE_DELETE);
    }

    /**
     * Check if a specific action is enabled in a configuration
     *
     * @param array<string, bool> $config The actions configuration
     * @param ActionType|string $action The action to check
     * @return bool
     */
    public static function isEnabled(array $config, ActionType|string $action): bool
    {
        $actionKey = $action instanceof ActionType ? $action->value : $action;
        return $config[$actionKey] ?? false;
    }

    /**
     * Merge configurations (later configurations override earlier ones)
     *
     * @param array<string, bool> ...$configs
     * @return array<string, bool>
     */
    public static function merge(array ...$configs): array
    {
        return array_merge(self::getDefaults(), ...$configs);
    }

    /**
     * Validate if an action name is supported
     *
     * @param ActionType|string $action
     * @return bool
     */
    public static function isValidAction(ActionType|string $action): bool
    {
        if ($action instanceof ActionType) {
            return true; // Enum cases are always valid
        }

        return ActionType::isValid($action);
    }

    /**
     * Get all available action types as enum cases
     *
     * @return ActionType[]
     */
    public static function getActionTypes(): array
    {
        return ActionType::cases();
    }

    /**
     * Get enabled actions from a configuration
     *
     * @param array<string, bool> $config
     * @return ActionType[]
     */
    public static function getEnabledActions(array $config): array
    {
        $enabled = [];

        foreach (ActionType::cases() as $action) {
            if ($config[$action->value] ?? false) {
                $enabled[] = $action;
            }
        }

        return $enabled;
    }

    /**
     * Get all enabled action names from a configuration (including custom actions)
     *
     * @param array<string, bool> $config Action configuration
     * @return array<string> Array of enabled action names
     */
    public static function getEnabled(array $config): array
    {
        return array_keys(array_filter($config));
    }

    /**
     * Convert string-based configuration to enum-based
     *
     * @param array<string, bool> $config
     * @return ActionType[]
     * @deprecated Use getEnabledActions() instead
     */
    public static function toEnums(array $config): array
    {
        return self::getEnabledActions($config);
    }

    /**
     * Create configuration from array of action names (backward compatibility)
     *
     * @param string[] $actionNames
     * @return array<string, bool>
     */
    public static function fromNames(array $actionNames): array
    {
        $result = self::none();

        foreach ($actionNames as $name) {
            if (ActionType::isValid($name)) {
                $result[$name] = true;
            }
        }

        return $result;
    }

    /**
     * Get all action values as an array
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn(ActionType $case) => $case->value, ActionType::cases());
    }

    /**
     * Get all action names as an array
     *
     * @return array<string>
     */
    public static function names(): array
    {
        return array_map(fn(ActionType $case) => $case->name, ActionType::cases());
    }

    /**
     * Get actions grouped by type
     */
    public static function getGrouped(): array
    {
        return [
            'read' => [ActionType::INDEX, ActionType::SHOW],
            'write' => [ActionType::CREATE, ActionType::EDIT],
            'delete' => [ActionType::DESTROY, ActionType::FORCE_DELETE, ActionType::RESTORE],
        ];
    }

    /**
     * Get actions that don't require soft deletes
     */
    public static function getBasicActions(): array
    {
        return [ActionType::INDEX, ActionType::CREATE, ActionType::SHOW, ActionType::EDIT];
    }

    /**
     * Get actions that require soft deletes
     */
    public static function getSoftDeleteActions(): array
    {
        return [ActionType::DESTROY, ActionType::FORCE_DELETE, ActionType::RESTORE];
    }
}
