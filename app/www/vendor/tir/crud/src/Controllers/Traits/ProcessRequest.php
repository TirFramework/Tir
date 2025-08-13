<?php

namespace Tir\Crud\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Tir\Crud\Support\Hooks\RequestHooks;
use Tir\Crud\Support\Database\DatabaseAdapterFactory;

trait ProcessRequest
{
    use RequestHooks;
    /**
     * Process the request data
     */
    private function processRequest(Request $request)
    {
        // Define the default behavior as a closure
        $defaultProcessRequest = function($req = null) use ($request) {
            if ($req !== null) {
                $request = $req;
            }

            $dataFields = collect($this->scaffolder()->getAllDataFields())
                ->pluck('request')->flatten()->unique()->toArray();

            //get only request that has equal field in scaffold
            $clearedRequest = [];
            $requestAll = $request->all();
            foreach ($requestAll as $key => $value) {
                if (in_array($key, $dataFields)) {
                    $clearedRequest[$key] = $value;
                }
            }

            // Replace request data with an empty array
            $request->replace([]);

            //convert dot string request to array
            $unDoted = Arr::undot($clearedRequest);

            $request->merge($unDoted);

            return $request;
        };

        // Pass the closure to the hook
        $customProcessRequest = $this->callHook('onProcessRequest', $defaultProcessRequest, $request);
        if($customProcessRequest !== null) {
            return $customProcessRequest;
        }

        // Otherwise, return the result directly
        return $defaultProcessRequest();
    }

    /**
     * Validate the create request
     */
    private function validateCreateRequest(Request $request)
    {
        // Define the default behavior as a closure
        $defaultStoreValidation = function($req = null) use ($request) {
            if ($req !== null) {
                $request = $req;
            }

            $rules = $this->scaffolder()->getCreationRules();
            $validator = Validator::make($request->all(), $rules);
            $validator->validate();

            return true;
        };

        // Pass the closure to the hook
        $customStoreValidation = $this->callHook('onStoreValidation', $defaultStoreValidation, $request);
        if($customStoreValidation !== null) {
            return $customStoreValidation;
        }

        // Otherwise, return the result directly
        return $defaultStoreValidation();
    }

    /**
     * Validate the update request
     */
    private function validateUpdateRequest(Request $request, $id)
    {
        // Define the default behavior as a closure
        $defaultUpdateValidation = function($req = null, $modelId = null) use ($request, $id) {
            if ($req !== null) {
                $request = $req;
            }
            if ($modelId !== null) {
                $id = $modelId;
            }

            $rules = $this->scaffolder()->getUpdateRules();
            $validator = Validator::make($request->all(), $rules);
            $validator->validate();

            $request = $this->passedValidation($request);

            return true;
        };

        // Pass the closure to the hook
        $customUpdateValidation = $this->callHook('onUpdateValidation', $defaultUpdateValidation, $request, $id);
        if($customUpdateValidation !== null) {
            return $customUpdateValidation;
        }

        // Otherwise, return the result directly
        return $defaultUpdateValidation();
    }

    private function passedValidation($request)
    {
        // Use database adapter for database-specific request processing
        $adapter = DatabaseAdapterFactory::create($this->model->getConnection());

        $requestData = $request->all();
        $processedData = $adapter->processRequestData($requestData);

        // Clear and merge the processed data
        foreach ($request->all() as $offset => $value) {
            $request->offsetUnset($offset);
        }
        $request->merge($processedData);

        return $request;
    }
}
