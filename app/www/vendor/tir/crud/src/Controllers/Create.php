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
        $this->callHookIfExists('onStore', $request);
        $model = $this->storeTransaction($request);
        return $this->response()->store($model, $this->scaffolder());
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
        $modelFillable = $this->model()->getFillable();
        $modelGuarded = $this->model()->getGuarded();

        $model = $this->model();

        // Fillable columns from scaffolder and model
        $model = $model->fillable($this->scaffolder()->getFillableColumns($modelFillable, $modelGuarded));

        // Allow hook before filling model data
        $model = $this->callHookIfExists('onBeforeFillModel',$model, $request);

        $model->fill($request->all());

        // Allow hook before saving the model
        $model = $this->callHookIfExists('onBeforeSaveModel', $model, $request);

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
