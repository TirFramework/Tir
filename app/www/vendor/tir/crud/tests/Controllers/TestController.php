<?php

namespace Tir\Crud\Tests\Controllers;

use Illuminate\Routing\Controller;
use Tir\Crud\Controllers\Traits\Crud;
use Tir\Crud\Tests\Scaffolders\TestScaffolder;

class TestController extends Controller
{
    use Crud;

    protected function setScaffolder(): string
    {
        return TestScaffolder::class;
    }

    public function scaffolder()
    {
        return new TestScaffolder();
    }

    // Removed setup method to avoid hook registration errors during testing
}
