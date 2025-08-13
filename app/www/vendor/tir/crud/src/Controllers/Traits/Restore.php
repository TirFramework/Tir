<?php

namespace Tir\Crud\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Support\Hooks\RestoreHooks;

trait Restore
{
    use RestoreHooks;
    

    public final function restore($id): JsonResponse
    {

        // Define the default behavior as a closure
        $defaultRestore = function($modelId = null) use ($id) {
            if ($modelId !== null) {
                $id = $modelId;
            }
            $item = $this->model()::onlyTrashed()->findOrFail($id);
            $item->restore();
            return $item;
        };

        // Pass the closure to the hook
        $customRestore = $this->callHook('onRestore', $defaultRestore, $id);
        if($customRestore !== null) {
            $restoredItem = $customRestore;
        } else {
            $restoredItem = $defaultRestore();
        }

        // Handle response with hooks
        return $this->restoreResponse($restoredItem);
    }

    private function restoreResponse($restoredItem): JsonResponse
    {
        // Define the default response behavior as a closure
        $defaultResponse = function($item = null) use ($restoredItem) {
            if ($item !== null) {
                $restoredItem = $item;
            }

            $moduleName = $this->scaffolder()->getModuleName();
            $message = trans('core::message.item-restored', ['item' => trans("message.item.$moduleName")]);

            return Response::json([
                'restored' => true,
                'message' => $message,
            ], 200);
        };

        // Pass the closure to the response hook
        $customResponse = $this->callHook('onRestoreResponse', $defaultResponse, $restoredItem);
        if($customResponse !== null) {
            return $customResponse;
        }

        // Return default response
        return $defaultResponse();
    }
}
