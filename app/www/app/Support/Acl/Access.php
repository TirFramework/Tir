<?php

namespace App\Support\Acl;

use Illuminate\Support\Facades\Auth;

/**
 * Access Control System - Handles permission checking
 *
 * Merges permissions from all user roles and checks if user has access
 * to perform specific actions on modules.
 *
 * Permission storage format:
 * ```
 * permissions: {
 *   "user": { "index": true, "create": true, "edit": false },
 *   "category": { "index": true, "show": true, "destroy": false }
 * }
 * ```
 */
class Access
{
    /**
     * Check if user has permission for an action on a module
     *
     * @param string $module Module name (e.g., 'user', 'category')
     * @param string $action Action name (e.g., 'index', 'create', 'edit')
     * @return bool Whether user has permission
     */
    public static function check(string $module, string $action): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Get merged permissions from all roles
        $mergedPermissions = self::getUserMergedPermissions($user);

        // Check if user has permission
        return $mergedPermissions[$module][$action] ?? false;
    }

    /**
     * Get all merged permissions for user
     *
     * Merges permissions from all assigned roles.
     * TRUE takes precedence over FALSE (most permissive wins).
     *
     * @param mixed $user User instance
     * @return array Merged permissions array
     */
    private static function getUserMergedPermissions($user): array
    {
        // Check cache on user instance
        $cacheKey = '_mergedPermissions_';
        if (isset($user->{$cacheKey})) {
            return $user->{$cacheKey};
        }

        // If no roles assigned, return empty permissions
        if (empty($user->role_ids)) {
            $user->{$cacheKey} = [];
            return $user->{$cacheKey};
        }

        // Load all roles assigned to user
        $roles = \App\Models\UserRole::whereIn('id', (array) $user->role_ids)->get();

        // Merge all permissions from all roles
        $mergedPermissions = [];
        foreach ($roles as $role) {
            if ($role->permissions) {
                $mergedPermissions = self::mergePermissions($mergedPermissions, $role->permissions);
            }
        }

        // Cache on user instance for this request
        $user->{$cacheKey} = $mergedPermissions;
        return $user->{$cacheKey};
    }

    /**
     * Merge two permission arrays
     *
     * TRUE takes precedence over FALSE (most permissive wins).
     * If a permission is true in any role, it's true for the user.
     *
     * @param array $existing Existing permissions
     * @param array $new New permissions to merge
     * @return array Merged permissions
     */
    private static function mergePermissions(array $existing, array $new): array
    {
        foreach ($new as $module => $actions) {
            if (!isset($existing[$module])) {
                $existing[$module] = [];
            }

            foreach ($actions as $action => $permission) {
                // True takes precedence over false
                if (!isset($existing[$module][$action]) || $permission === true) {
                    $existing[$module][$action] = $permission;
                }
            }
        }

        return $existing;
    }

    /**
     * Clear cached permissions for a user
     *
     * Call after updating user's roles.
     *
     * @param mixed $user User instance
     * @return void
     */
    public static function clearCache($user): void
    {
        unset($user->_mergedPermissions_);
    }
}
