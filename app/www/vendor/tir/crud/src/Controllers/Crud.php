<?php

namespace Tir\Crud\Controllers;

use Illuminate\Support\Facades\Route;
use Tir\Crud\Support\Requests\CrudRequest;
use Tir\Crud\Support\Response\CrudResponse;

trait Crud
{
    use IndexTrait, DataTrait, ShowTrait, CreateTrait, EditTrait, DestroyTrait;

    private mixed $model;

    protected abstract function setModel(): string;

    public function __construct()
    {
        $this->modelInit();
        $this->addBasicsToRequest();
        $this->checkAccess();

//         $this->middleware(function($request, $next){
//             $this->CrudRequestInjector();
//             $this->crudInit();
//             return $next($request);
//         });

        $this->CrudRequestInjector();
        $this->crudInit();
    }

    public function model()
    {
        return $this->model;
    }

    public function setRequest(): string
    {
        return '';
    }

    public function setResponse(): string
    {
        return CrudResponse::class;
    }


    protected final function response(){
        $response = $this->setResponse();
        return new $response;
    }

    private function CrudRequestInjector(): void
    {
        $formRequest = $this->setRequest();
        if($formRequest){
            app()->singleton(CrudRequest::class, function ($app) use ($formRequest) {
                return new $formRequest;
            });
        }
    }

    private function modelInit(): void
    {
        $model = $this->setModel();
        $this->model = new $model;
    }

    private function addBasicsToRequest(): void
    {
        $route = Route::getCurrentRoute();
        if($route) {
            $action = explode('@', Route::getCurrentRoute()->getActionName())[1];
            request()->merge([
                'crudModel'=>$this->model(),
                'crudModuleName' => $this->model()->getModuleName(),
                'crudActionName'=>$action
            ]);
        }

    }

    protected function crudInit(): void
    {
    }


    private function checkAccess(): void
    {
        // if($this->model()->getAccessLevelStatus() && config('crud.accessLevelControl') != 'off'){
        //     $this->middleware('acl:'.$this->model()->getModuleName());
        //  }

    }


}
