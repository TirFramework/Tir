<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Scaffolders\UserScaffolder;
use Tir\Crud\Controllers\CrudController;


class UserController extends CrudController
{
    public function setScaffolder(): string
    {
        return UserScaffolder::class;
    }

    public function setup()
    {
        $this->accessControlEnabled = false;
    }

}
