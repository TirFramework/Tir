<?php

namespace App\Http\Controllers;

use App\Models\User;
use Tir\Crud\Controllers\CrudController;


class UserController extends CrudController
{

    public function setModel(): string
    {
        return User::class;
    }

}
