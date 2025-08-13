<?php

namespace Tir\Crud\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Services\DataService;
use Tir\Crud\Support\Hooks\TrashHooks;

trait Trash
{
    use TrashHooks;
    

    public final function trashData(): JsonResponse
    {

        // Create DataService with trash mode
        $CrudService = new DataService($this->scaffolder(), $this->model());

        // Pass hooks from controller to service
        if (isset($this->crudHookCallbacks)) {
            $CrudService->setHooks($this->crudHookCallbacks);
        }

        // Get trash data
        $items = $CrudService->getData(true); // true for onlyTrashed

        // Handle response with hooks
        return $this->trashResponse($items);
    }

    private function trashResponse($items): JsonResponse
    {
        // Define the default response behavior as a closure
        $defaultResponse = function($i = null) use ($items) {
            if ($i !== null) {
                $items = $i;
            }
            return Response::json($items, 200);
        };

        // Pass the closure to the response hook
        $customResponse = $this->callHook('onTrashResponse', $defaultResponse, $items);
        if($customResponse !== null) {
            return $customResponse;
        }

        // Return default response
        return $defaultResponse();
    }
}
