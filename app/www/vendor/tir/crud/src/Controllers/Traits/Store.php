<?php

namespace Tir\Crud\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Services\StoreService;
use Tir\Crud\Support\Hooks\StoreHooks;
use Tir\Crud\Support\Hooks\RequestHooks;

trait Store
{
    use ProcessRequest;
    use StoreHooks;
    use RequestHooks;

    public final function store(Request $request): JsonResponse
    {
        // Access check is now handled automatically in callAction()

        // First process the request
        $processedRequest = $this->processRequest($request);

        // Then validate the processed request
        $this->validateCreateRequest($processedRequest);

        // Finally store the data
        return $this->storeCrud($processedRequest);
    }

    private function storeCrud($request): JsonResponse
    {
        // Define the default behavior as a closure
        $defaultStore = function($req = null) use ($request) {
            if ($req !== null) {
                $request = $req;
            }

            // Create and configure service
            $service = new StoreService($this->scaffolder(), $this->model());

            // Pass hooks to service
            if (isset($this->crudHookCallbacks)) {
                $service->setHooks($this->crudHookCallbacks);
            }

            // Execute store logic in service
            return $service->store($request);
        };

        // Pass the closure to the hook
        $customStore = $this->callHook('onStore', $defaultStore, $request);
        if($customStore !== null) {
            $model = $customStore;
        } else {
            $model = $defaultStore();
        }

        // Handle response with hooks
        return $this->storeResponse($model);
    }

    private function storeResponse($model): JsonResponse
    {
        // Define the default response behavior as a closure
        $defaultResponse = function($m = null) use ($model) {
            if ($m !== null) {
                $model = $m;
            }

            $moduleName = $this->scaffolder()->getModuleName();
            $message = trans('core::message.item-created', ['item' => trans("message.item.$moduleName")]);

            return Response::json([
                'id'      => $model->id,
                'created' => true,
                'message' => $message,
            ], 200);
        };

        // Pass the closure to the response hook
        $customResponse = $this->callHook('onStoreResponse', $defaultResponse, $model);
        if($customResponse !== null) {
            return $customResponse;
        }

        // Return default response
        return $defaultResponse();
    }
}
