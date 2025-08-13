<?php

namespace Tir\Crud\Controllers\Traits;

use Illuminate\Support\Facades\Route;
use Tir\Crud\Support\Scaffold\Actions;


trait CrudInit
{

    private mixed $model;
    private mixed $scaffolder;

    protected abstract function setScaffolder(): string;

    public function __construct()
    {
        $this->scaffolderInit();

        // Auto setup crud hooks if method exists
        if (method_exists($this, 'setup')) {
            $this->setup();
        }
    }

    protected final function model()
    {
        return $this->model;
    }

    protected final function scaffolder()
    {
        return $this->scaffolder;
    }

    private function scaffolderInit(): void
    {

        $s = $this->setScaffolder();
        $this->scaffolder = new $s;

        $m = $this->scaffolder->model();
        $this->model = new $m;
    }

    public function callAction($method, $parameters)
    {
        // Auto-check access before calling ANY method

        // Then call the actual method
        return parent::callAction($method, $parameters);
    }

}
