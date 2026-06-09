<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * UserRole Model - Represents a user role with permissions
 *
 * Stores role name and permission matrix for access control.
 *
 * Attributes:
 * - title: string - Role name (e.g., 'Admin', 'Editor')
 * - type: string - Role type (admin, partner, employer)
 * - permissions: json - Permission matrix
 *
 * Permission format:
 * {
 *   "user": { "index": true, "create": true, "edit": false },
 *   "category": { "index": true, "show": true, "destroy": false }
 * }
 */
class UserRole extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'type',
        'permissions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'permissions' => 'json',
    ];


    /**
     * Get all users with this role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role_user', 'role_id', 'user_id');
    }

    /**
     * Check if user has specific permission
     *
     * @param string $module Module name
     * @param string $action Action name
     * @return bool
     */
    public function hasPermission(string $module, string $action): bool
    {
        return $this->permissions[$module][$action] ?? false;
    }

    /**
     * Get all permissions for a module
     *
     * @param string $module Module name
     * @return array
     */
    public function getModulePermissions(string $module): array
    {
        return $this->permissions[$module] ?? [];
    }
}
