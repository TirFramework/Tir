<?php

namespace Tir\Crud\Controllers;

use App\Http\Controllers\Controller;

abstract class CrudController extends Controller
{
    use CrudInitTrait, IndexTrait, DataTrait, ShowTrait, CreateTrait, EditTrait, DestroyTrait;

}
