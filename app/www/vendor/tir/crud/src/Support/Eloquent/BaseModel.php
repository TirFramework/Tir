<?php


namespace Tir\Crud\Support\Eloquent;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tir\Crud\Support\Scaffold\BaseScaffold;

abstract class BaseModel extends Model
{
    use BaseScaffold;

    use SoftDeletes;

    protected $dates = ['deleted_at'];


}
