<?php

namespace App\Http\Controllers\Admin;

use Tir\Crud\Controllers\CrudController;


/**
 * MinimalExampleController - Demonstrating Clean Architecture
 *
 * This controller demonstrates how simple CRUD controllers become
 * when using the new clean architecture approach.
 */
class MinimalExampleController extends CrudController
{
    protected function setScaffolder(): string
    {
        return \App\Scaffolders\MinimalExampleScaffolder::class;
    }

}
