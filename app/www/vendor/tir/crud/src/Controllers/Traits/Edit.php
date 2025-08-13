<?php

namespace Tir\Crud\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Support\Hooks\EditHooks;

trait Edit
{
    use EditHooks;

    public final function edit(int|string $id): JsonResponse
    {
        // Access check is now handled automatically in callAction()

        // Define the default behavior as a closure
        $defaultEdit = function($modelId = null) use ($id) {
            if ($modelId !== null) {
                $id = $modelId;
            }
            return $this->model()->findOrFail($id);
        };

        // Pass the closure to the hook
        $customEdit = $this->callHook('onEdit', $defaultEdit, $id);
        if($customEdit !== null) {
            $dataModel = $customEdit;
        } else {
            $dataModel = $defaultEdit();
        }

        // Handle response with hooks
        return $this->editResponse($dataModel);
    }

    private function editResponse($dataModel): JsonResponse
    {
        // Define the default response behavior as a closure
        $defaultResponse = function($model = null) use ($dataModel) {
            if ($model !== null) {
                $dataModel = $model;
            }
            $scaffold = $this->scaffolder()->getEditScaffold($dataModel);

            // Override actions with access-filtered ones
            $scaffold['configs']['actions'] = $this->getAvailableActions();

            return Response::json($scaffold, 200);
        };

        // Pass the closure to the response hook
        $customResponse = $this->callHook('onEditResponse', $defaultResponse, $dataModel);
        if($customResponse !== null) {
            return $customResponse;
        }

        // Prepare and return the default response
        return $defaultResponse();
    }
}
