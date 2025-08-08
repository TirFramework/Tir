<?php

namespace Tir\Crud\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Tir\Crud\Support\Hooks\RequestHooks;

trait ProcessRequest
{
    use RequestHooks;
    /**
     * Process the request data
     */
    protected function processRequest(Request $request)
    {
        // Allow hook to preprocess request data
        $$request = $this->callHookIfExists('onBeforeProcessRequest', $request);


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


        // Allow custom manipulation of request data
        $request = $this->callHookIfExists('onAfterProcessRequest', $request);


        return $request;

    }

    /**
     * Validate the create request
     */
    protected function validateCreateRequest(Request $request)
    {
        $rules = $this->scaffolder()->getCreationRules();

        // Allow hook to modify validation rules
        $rules = $this->callHookIfExists('onBeforeStoreValidation', $rules, $request);

        $validator = Validator::make($request->all(), $rules);

        $validator->validate();

        // Allow hook after validation passes
        $this->callHookIfExists('onAfterStoreValidation', $request);

        return true;
    }

    /**
     * Validate the update request
     */
    protected function validateUpdateRequest(Request $request, $id)
    {
        $rules = $this->scaffolder()->getUpdateRules();
        // $messages = $this->getValidationMessages();

        // Allow hook to modify validation rules
        $rules = $this->callHookIfExists('onBeforeUpdateValidation', $rules, $request, $id);

        $validator = Validator::make($request->all(), $rules);

        $validator->validate();

        // Allow hook after validation passes
        $this->callHookIfExists('onAfterUpdateValidation', $request, $id);

        $request = $this->passedValidation($request);

        return true;
    }

    /**
     * Group array items with numeric indexes
     */
    protected function groupByNumber(array $array): array
    {
        $result = array();

        foreach ($array as $key => $value) {
            $parts = preg_split('/\.\d+\./', $key);
            if (count($parts) == 1) {
                $result[$key] = $value;
            } else {
                preg_match('/\.\d+\./', $key, $matches);
                $index = str_replace('.', '', $matches)[0] ?? null;
                $prefix = $parts[0] ?? null;
                $suffix = $parts[1] ?? null;

                if ($suffix) {
                    $result[$prefix][$index][$suffix] = $value;
                } else {
                    $result[$prefix][$index] = $value;
                }
            }
        }

        return $result;
    }




    protected function passedValidation($request)
    {
        //make ready request for mongodb
        if ($this->model->getConnection()->getDriverName() === 'mongodb') {
             $requestTemp = $request->all();

            foreach ($request->all() as $offset => $value) {
                $request->offsetUnset($offset);
            }
            $request->merge($this->groupByNumber($requestTemp));
        }

        return $request;
    }
}
