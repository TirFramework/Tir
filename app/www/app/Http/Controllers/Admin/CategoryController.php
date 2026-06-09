<?php

namespace App\Http\Controllers\Admin;

use Tir\Crud\Controllers\CrudController;

/**
 * CategoryController - Category CRUD Operations
 *
 * Provides REST API endpoints for category management.
 * Auto-generates: index, show, create, store, edit, update, destroy, trash, restore, forceDelete
 */
class CategoryController extends CrudController
{
    /**
     * Set the scaffolder for this CRUD controller
     */
    protected function setScaffolder(): string
    {
        return \App\Scaffolders\CategoryScaffolder::class;
    }

    /**
     * Setup controller hooks and configuration
     */
    protected function setup()
    {
        // Disable access control for demo purposes
        $this->accessControlEnabled = false;
    }
}
