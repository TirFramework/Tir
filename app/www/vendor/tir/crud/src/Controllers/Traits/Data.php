<?php

namespace Tir\Crud\Controllers\Traits;

use Tir\Crud\Services\DataService;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Support\Hooks\IndexDataHooks;


trait Data
{

    use IndexDataHooks;


    public final function data(): mixed
    {
        $CrudService = new DataService($this->scaffolder(), $this->model());

        // Pass hooks from controller to service
        if (isset($this->crudHookCallbacks)) {
            $CrudService->setHooks($this->crudHookCallbacks);
        }

        $items = $CrudService->getData();
        $response = $this->indexResponse($items);

        return $response;

    }


    private function indexResponse($items): mixed
    {
        // Define the default behavior as a closure
        $defaultResponse = function($i = null) use ($items) {
            if ($i !== null) {
                $items = $i;
            }
            return Response::json($items, 200);
        };

        // Pass the closure to the hook
        $customResponse = $this->callHook('onIndexResponse', $defaultResponse, $items);

        if($customResponse !== null) {
            return $customResponse;
        }

        // Otherwise, return the result directly
        return $defaultResponse();
    }


}
