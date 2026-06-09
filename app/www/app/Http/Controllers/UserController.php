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

        // Hook: Process password hashing and email normalization
        $this->onProcessRequest(function($defaultProcessRequest, $request) {
            // Normalize email to lowercase
            if ($request->email) {
                $request->merge(['email' => strtolower($request->email)]);
            }

            // Hash password if provided
            if ($request->new_password) {
                $request->merge(['password' => bcrypt($request->new_password)]);
            }

            return $defaultProcessRequest($request);
        });
    }
}
