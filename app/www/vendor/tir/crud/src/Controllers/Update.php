<?php

namespace Tir\Crud\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

trait Update
{
    public function edit($id): JsonResponse
    {
        $fields = $this->scaffolder()->getEditScaffold();
        return Response::json($fields, '200');
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        // Process and validate the request
        $processedRequest = $this->processRequest($request, 'update');

        return $this->updateCrud($processedRequest, $id);
    }

    protected final function updateCrud($request, $id): \Illuminate\Http\JsonResponse
    {
        $model = $this->updateTransaction($request, $id);
        return $this->response()->update($model);
    }

    protected final function updateTransaction($request, $id)
    {
        return DB::transaction(function () use ($request, $id) { // Start the transaction
            $model = $this->updateModel($request, $id);
            DB::commit();
            return $model;
        });
    }

    /**
     * This function updates model and its relations
     */
    protected final function updateModel($request, $id)
    {
        // Find model
        $model = $this->model()->findOrFail($id);

        // Update model
        $model->fillable($this->scaffolder()->getFillableColumns());
        $model->fill($request->all());
        $model->save();

        // Update relations
        $this->updateRelations($request, $model);

        return $model;
    }

    protected final function updateRelations(Request $request, $model): void
    {
        foreach ($this->scaffolder()->getAllDataFields() as $field) {
            if (isset($field->relation) && $field->multiple) {
                $data = $request->input($field->name);
                if (isset($data)) {
                    $model->{$field->relation->name}()->sync($data);
                }
            }
        }
    }
}
