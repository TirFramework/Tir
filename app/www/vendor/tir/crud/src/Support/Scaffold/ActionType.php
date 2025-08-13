<?php

namespace Tir\Crud\Support\Scaffold;

/**
 * Enum defining all available CRUD actions
 *
 * Provides type safety and IDE autocompletion for action names.
 * Keeps the enum simple and focused on just defining the action cases.
 *
 * Usage examples:
 * - ActionType::INDEX
 * - ActionType::CREATE->value
 * - Actions::isEnabled(ActionType::EDIT)
 */
enum ActionType: string
{
    case INDEX = 'index';
    case CREATE = 'create';
    case SHOW = 'show';
    case EDIT = 'edit';
    case DESTROY = 'destroy';
    case FORCE_DELETE = 'forceDelete';
    case RESTORE = 'restore';

    /**
     * Check if a string is a valid action
     *
     * @param string $action
     * @return bool
     */
    public static function isValid(string $action): bool
    {
        return self::tryFrom($action) !== null;
    }

    /**
     * Get human-readable label for the action
     */
    public function label(): string
    {
        return match($this) {
            self::INDEX => 'List/View All',
            self::CREATE => 'Create New',
            self::SHOW => 'View Details',
            self::EDIT => 'Edit/Update',
            self::DESTROY => 'Soft Delete',
            self::FORCE_DELETE => 'Permanent Delete',
            self::RESTORE => 'Restore Deleted',
        };
    }
}
