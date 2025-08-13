<?php

namespace Tir\Crud\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Support\Hooks\DestroyHooks;

trait Destroy
{
    use DestroyHooks;
    use Restore;
    use ForceDelete;
    

    public final function destroy($id): JsonResponse
    {

        // Define the default behavior as a closure
        $defaultDestroy = function($modelId = null) use ($id) {
            if ($modelId !== null) {
                $id = $modelId;
            }
            $item = $this->model()->findOrFail($id);
            $item->delete();
            return $item;
        };

        // Pass the closure to the hook
        $customDestroy = $this->callHook('onDestroy', $defaultDestroy, $id);
        if($customDestroy !== null) {
            $deletedItem = $customDestroy;
        } else {
            $deletedItem = $defaultDestroy();
        }

        // Handle response with hooks
        return $this->destroyResponse($deletedItem);
    }

    private function destroyResponse($deletedItem): JsonResponse
    {
        // Define the default response behavior as a closure
        $defaultResponse = function($item = null) use ($deletedItem) {
            if ($item !== null) {
                $deletedItem = $item;
            }
            $moduleName = $this->scaffolder()->getModuleName();
            $message = trans('core::message.item-deleted', ['item' => trans("message.item.$moduleName")]);

            return Response::json([
                'deleted' => true,
                'message' => $message,
            ], 200);
        };

        // Pass the closure to the response hook
        $customResponse = $this->callHook('onDestroyResponse', $defaultResponse, $deletedItem);
        if($customResponse !== null) {
            return $customResponse;
        }

        // Return default response
        return $defaultResponse();
    }
}
