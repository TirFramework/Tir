<?php

namespace Tir\Crud\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Support\Hooks\ShowHooks;

trait Show
{
    use ShowHooks;
    

    public final function show($id): JsonResponse
    {

        // Define the default behavior as a closure
        $defaultShow = function($modelId = null) use ($id) {
            if ($modelId !== null) {
                $id = $modelId;
            }
            return $this->model()->findOrFail($id);
        };

        // Pass the closure to the hook
        $customShow = $this->callHook('onShow', $defaultShow, $id);
        if($customShow !== null) {
            $dataModel = $customShow;
        } else {
            $dataModel = $defaultShow();
        }

        // Define the default response behavior as a closure
        $defaultResponse = function($model = null) use ($dataModel) {
            if ($model !== null) {
                $dataModel = $model;
            }
            $scaffold = $this->scaffolder()->getDetailScaffold($dataModel);
            return Response::json($scaffold, 200);
        };

        // Pass the closure to the response hook
        $customResponse = $this->callHook('onShowResponse', $defaultResponse, $dataModel);
        if($customResponse !== null) {
            return $customResponse;
        }

        // Prepare and return the default response
        return $defaultResponse();
    }
}
