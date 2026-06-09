<?php

namespace App\Http\Controllers;

use Tir\Crud\Controllers\CrudController;
use App\Scaffolders\SampleModelScaffolder;

class SampleModelController extends CrudController
{

    protected function setScaffolder(): string
    {
        return SampleModelScaffolder::class;
    }

    protected function setup()
    {

        $this->onRelation(function ($relations, $query) {
            $relations(['authors']);
        });
    }
}
