<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tir\Crud\Support\Scaffold\BaseScaffold;
use Tir\Crud\Support\Scaffold\Fields\Button;
use Tir\Crud\Support\Scaffold\Fields\Password;
use Tir\Crud\Support\Scaffold\Fields\Text;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use BaseScaffold;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function setModuleName(): string
    {
        return 'user';
    }

    protected function setAcl(): string
    {
        return false;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function setFields(): array
    {
        return [
            Text::make('name'),
            Text::make('email'),
            Password::make('password')->hideFromIndex(),
        ];
    }

    protected function setButtons(): array
    {
        return [
            Button::make('cancel')->action('Cancel')->path('/admin/user'),
            Button::make('submit')->action('Submit'),
        ];
    }
}
