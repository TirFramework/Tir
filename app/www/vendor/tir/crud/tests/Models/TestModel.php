<?php

namespace Tir\Crud\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestModel extends Model
{
    use SoftDeletes;

    protected $table = 'test_models';

    protected $fillable = [
        'name',
        'email',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
