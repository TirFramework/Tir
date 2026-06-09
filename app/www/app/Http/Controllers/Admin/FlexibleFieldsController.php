<?php

namespace App\Http\Controllers\Admin;

use Tir\Crud\Controllers\CrudController;

/**
 * FlexibleFieldsController - Field Types Demonstration
 *
 * This controller demonstrates the flexibility of the field system
 * by showcasing various field types and configurations.
 *
 * Auto-generates: index, show, create, store, edit, update, destroy
 */
class FlexibleFieldsController extends CrudController
{
    /**
     * Set the scaffolder for this demo
     */
    protected function setScaffolder(): string
    {
        return \App\Scaffolders\FlexibleFieldsScaffolder::class;
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
