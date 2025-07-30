<?php

namespace Tir\Crud\Controllers;

use Illuminate\Support\Facades\View;
use Tir\Crud\Events\TrashEvent;

trait TrashTrait
{

    public function trash()
    {
        return View::first(["$this->name::admin.index", "core::scaffold.index"])->with(['crud' => $this->crud, 'trash' => true]);

    }

}
