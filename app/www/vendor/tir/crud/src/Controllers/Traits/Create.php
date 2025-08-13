<?php

namespace Tir\Crud\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Support\Hooks\CreateHooks;

trait Create
{
    use CreateHooks;
    use ActionValidation;

    public final function create(): JsonResponse
    {
        $this->checkAction('create');

        // Define the default behavior as a closure
        $defaultCreate = function() {
            return $this->scaffolder()->getCreateScaffold();
        };

        // Pass the closure to the hook
        $customCreate = $this->callHook('onCreate', $defaultCreate);
        if($customCreate !== null) {
            $fields = $customCreate;
        } else {
            $fields = $defaultCreate();
        }

        // Handle response with hooks
        return $this->createResponse($fields);
    }

    private function createResponse($fields): JsonResponse
    {
        // Define the default response behavior as a closure
        $defaultResponse = function($fieldsData = null) use ($fields) {
            if ($fieldsData !== null) {
                $fields = $fieldsData;
            }
            return Response::json($fields, 200);
        };

        // Pass the closure to the response hook
        $customResponse = $this->callHook('onCreateResponse', $defaultResponse, $fields);
        if($customResponse !== null) {
            return $customResponse;
        }

        // Prepare and return the default response
        return $defaultResponse();
    }

}
