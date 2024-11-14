<?php

namespace App\Http\Controllers;

use App\Models\User;
use Tir\Crud\Controllers\CrudController;

class UserController extends CrudController
{

    protected function setModel(): string
    {
        return User::class;
    }
}
