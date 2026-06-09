<?php

namespace App\Http\Controllers\Admin;

use App\Scaffolders\UserRoleScaffolder;
use Tir\Crud\Controllers\CrudController;

/**
 * UserRoleController - Manages user roles and permissions
 *
 * Provides full CRUD API for managing roles with permission matrices.
 * Roles can be assigned to users to control access to modules.
 */
class UserRoleController extends CrudController
{
    /**
     * Set the scaffolder for this controller
     */
    public function setScaffolder(): string
    {
        return UserRoleScaffolder::class;
    }

    /**
     * Setup controller hooks
     *
     * Enable access control for this module
     */
    public function setup()
    {
        // For demo purposes, disable access control
        // In production, enable and check userRole permissions
        $this->accessControlEnabled = false;
    }
}
