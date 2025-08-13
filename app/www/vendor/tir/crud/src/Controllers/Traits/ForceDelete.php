<?php

namespace Tir\Crud\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Support\Hooks\ForceDeleteHooks;

trait ForceDelete
{
    use ForceDeleteHooks;
    

    public final function forceDelete($id): JsonResponse
    {

        // Define the default behavior as a closure
        $defaultForceDelete = function($modelId = null) use ($id) {
            if ($modelId !== null) {
                $id = $modelId;
            }
            $item = $this->model()->withTrashed()->findOrFail($id);
            $item->forceDelete();
            return $item;
        };

        // Pass the closure to the hook
        $customForceDelete = $this->callHook('onForceDelete', $defaultForceDelete, $id);
        if($customForceDelete !== null) {
            $deletedItem = $customForceDelete;
        } else {
            $deletedItem = $defaultForceDelete();
        }

        // Handle response with hooks
        return $this->forceDeleteResponse($deletedItem);
    }

    private function forceDeleteResponse($deletedItem): JsonResponse
    {
        // Define the default response behavior as a closure
        $defaultResponse = function($item = null) use ($deletedItem) {
            if ($item !== null) {
                $deletedItem = $item;
            }

            $moduleName = $this->scaffolder()->getModuleName();
            $message = trans('core::message.item-permanently-deleted', ['item' => trans("message.item.$moduleName")]);

            return Response::json([
                'permanently_deleted' => true,
                'message' => $message,
            ], 200);
        };

        // Pass the closure to the response hook
        $customResponse = $this->callHook('onForceDeleteResponse', $defaultResponse, $deletedItem);
        if($customResponse !== null) {
            return $customResponse;
        }

        // Return default response
        return $defaultResponse();
    }

    public final function emptyTrash(): JsonResponse
    {
        // Define the default behavior as a closure
        $defaultEmptyTrash = function() {
            $count = $this->model()::onlyTrashed()->count();
            $this->model()::onlyTrashed()->forceDelete();
            return $count;
        };

        // Pass the closure to the hook
        $customEmptyTrash = $this->callHook('onEmptyTrash', $defaultEmptyTrash);
        if($customEmptyTrash !== null) {
            $deletedCount = $customEmptyTrash;
        } else {
            $deletedCount = $defaultEmptyTrash();
        }

        // Handle response with hooks
        return $this->emptyTrashResponse($deletedCount);
    }

    private function emptyTrashResponse($deletedCount): JsonResponse
    {
        // Define the default response behavior as a closure
        $defaultResponse = function($count = null) use ($deletedCount) {
            if ($count !== null) {
                $deletedCount = $count;
            }

            $moduleName = $this->scaffolder()->getModuleName();
            $message = trans('core::message.trash-emptied', [
                'count' => $deletedCount,
                'item' => trans("message.item.$moduleName")
            ]);

            return Response::json([
                'trash_emptied' => true,
                'deleted_count' => $deletedCount,
                'message' => $message,
            ], 200);
        };

        // Pass the closure to the response hook
        $customResponse = $this->callHook('onEmptyTrashResponse', $defaultResponse, $deletedCount);
        if($customResponse !== null) {
            return $customResponse;
        }

        // Return default response
        return $defaultResponse();
    }
}
