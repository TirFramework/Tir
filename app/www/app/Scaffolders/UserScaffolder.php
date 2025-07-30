<?php

namespace App\Scaffolders;

use Tir\Crud\Support\Scaffold\Fields\Text;
use Tir\Crud\Support\Scaffold\BaseScaffold;
use Tir\Crud\Support\Scaffold\Fields\Password;

trait UserScaffolder
{
    use BaseScaffold;



    public function setModuleName(): string
    {
        return 'user';
    }
    public function setFields(): array
    {
        return [
            Text::make('name')
                ->display('Name')
                ->rules(['required', 'max:255']),
            Text::make('email')
                ->display('Email')
                ->rules(['required', 'email', 'max:255']),
            Password::make('password')
                ->display('Password')
                ->rules(['required'])
        ];
    }
}
