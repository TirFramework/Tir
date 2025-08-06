<?php

namespace Tir\Crud\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Support\Hooks\StoreHooks;

trait Create
{
    use ProcessRequest;
    use StoreHooks;

    /**
     * Set custom validation hook for create
     */
    protected function onValidateCreate(callable $callback): self
    {
        $this->crudHookCallbacks['validateCreateRequest'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for request processing
     */
    protected function onProcessRequest(callable $callback): self
    {
        $this->crudHookCallbacks['processRequest'] = $callback;
        return $this;
    }

    public function create(): JsonResponse
    {
        $fields = $this->scaffolder()->getCreateScaffold();
        return Response::json($fields, '200');
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        // First process the request
        $processedRequest = $this->processRequest($request);

        // Then validate the processed request
        $this->validateCreateRequest($processedRequest);

        // Finally store the data
        return $this->storeCrud($processedRequest);
    }

    protected final function storeCrud($request): \Illuminate\Http\JsonResponse
    {
        $model = $this->storeTransaction($request);
        return $this->response()->store($model);
    }

    protected final function storeTransaction($request)
    {
        return DB::transaction(function () use ($request) { // Start the transaction
            $id = $this->storeModel($request);
            DB::commit();
            return $id;
        });
    }

    /**
     * This function store crud and relations
     */
    protected final function storeModel($request)
    {
        // Store model
        $this->model()->fillable($this->scaffolder()->getFillableColumns());

        // Allow hook before filling model data
        $request = $this->callHookIfExists('onBeforeFillModel', $request);

        $this->model()->fill($request->all());

        // Allow hook before saving the model
        $model = $this->callHookIfExists('onBeforeSaveModel', $this->model(), $request);

        $model->save();

        // Allow hook after saving the model
        $model = $this->callHookIfExists('onAfterSaveModel', $model, $request);

        // Store relations
        $this->storeRelations($request);

        // Allow hook after everything is stored
        $model = $this->callHookIfExists('onAfterStoreCompleted', $model, $request);

        return $model;
    }

    protected final function storeRelations(Request $request): void
    {
        $model = $this->model();

        // Allow hook before storing relations
        $this->callHookIfExists('onBeforeStoreRelations', $model, $request);

        foreach ($this->scaffolder()->getAllDataFields() as $field) {
            if (isset($field->relation) && $field->multiple) {
                $data = $request->input($field->name);
                if (isset($data)) {
                    // Allow hook for specific relation
                    $data = $this->callHookIfExists('onBeforeStoreRelation', $data, $field->name, $model, $request);

                    $model->{$field->relation->name}()->sync($data);

                    // Allow hook after storing specific relation
                    $this->callHookIfExists('onAfterStoreRelation', $field->name, $model, $request);
                }
            }
        }

        // Allow hook after all relations are stored
        $this->callHookIfExists('onAfterStoreRelations', $model, $request);
    }


}
