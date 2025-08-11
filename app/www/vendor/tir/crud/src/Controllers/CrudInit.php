<?php

namespace Tir\Crud\Controllers;

use Tir\Crud\Support\Response\CrudResponse;

trait CrudInit
{


    private mixed $model;
    private mixed $scaffolder;

    protected abstract function setScaffolder(): string;

    public function __construct()
    {
        $this->scaffolderInit();
        // $this->checkAccess();

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
    protected function setRequest(): string
    {
        return '';
    }

    protected function setResponse(): string
    {
        return CrudResponse::class;
    }


    protected final function response(){
        $response = $this->setResponse();
        return new $response;
    }


    private function scaffolderInit(): void
    {
        $s = $this->setScaffolder();
        $this->scaffolder = new $s;

        $m = $this->scaffolder->model();
        $this->model = new $m;
    }



    private function checkAccess(): void
    {
        // if($this->model()->getAccessLevelStatus() && config('crud.accessLevelControl') != 'off'){
        //     $this->middleware('acl:'.$this->model()->getModuleName());
        //  }

    }


}
