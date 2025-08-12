<?php

namespace Tir\Crud\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestCategory extends Model
{
    use SoftDeletes;

    protected $table = 'test_categories';

    protected $fillable = [
        'title',
        'slug'
    ];
}
