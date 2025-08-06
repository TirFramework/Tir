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
        $data = $this->callHookIfExists('onBeforeProcessRequest', $request->all(), $request);

        if (is_array($data)) {
            // Replace relations with their IDs
            foreach ($this->getRelations() as $relation => $config) {
                if (isset($data[$relation]) && is_array($data[$relation])) {
                    $relationKey = $config['key'] ?? 'id';
                    $data[$relation] = collect($data[$relation])->pluck($relationKey)->toArray();
                }
            }

            // Allow custom manipulation of request data
            $data = $this->callHookIfExists('onAfterProcessRequest', $data, $request);
        }

        return $data;
    }

    /**
     * Validate the create request
     */
    protected function validateCreateRequest(Request $request)
    {
        $rules = $this->getValidationRules();
        $messages = $this->getValidationMessages();

        // Allow hook to modify validation rules
        $rules = $this->callHookIfExists('onBeforeValidateCreate', $rules, $request);

        $validator = Validator::make($request->all(), $rules, $messages);

        // Allow hook to modify validator
        $validator = $this->callHookIfExists('onValidatorCreated', $validator, $request);

        $validator->validate();

        // Allow hook after validation passes
        $this->callHookIfExists('onAfterValidateCreate', $request);

        return true;
    }

    /**
     * Validate the update request
     */
    protected function validateUpdateRequest(Request $request, $id)
    {
        $rules = $this->getValidationRules(false);
        $messages = $this->getValidationMessages();

        // Allow hook to modify validation rules
        $rules = $this->callHookIfExists('onBeforeValidateUpdate', $rules, $request, $id);

        $validator = Validator::make($request->all(), $rules, $messages);

        // Allow hook to modify validator
        $validator = $this->callHookIfExists('onValidatorCreated', $validator, $request);

        $validator->validate();

        // Allow hook after validation passes
        $this->callHookIfExists('onAfterValidateUpdate', $request, $id);

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
}
