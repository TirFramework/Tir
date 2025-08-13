<?php

namespace Tir\Crud\Controllers\Traits;


trait Crud
{
    use CrudInit, Index, Show, Create, Store, Edit, Update, Destroy, Trash;
}
