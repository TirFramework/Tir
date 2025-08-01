<?php

namespace Tir\Crud\Controllers;

use App\Http\Controllers\Controller;

abstract class CrudController extends Controller
{
    use CrudInit, Index, Data, Show, Create, Edit, Destroy;

}
