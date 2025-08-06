<?php

namespace Tir\Crud\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tir\Crud\Support\Hooks\UpdateHooks;

trait Edit
{
    use ProcessRequest;
    use UpdateHooks;

    public function edit(int|string $id)
    {
        $dataModel = $this->model()->findOrFail($id);
        return $this->scaffolder()->getEditScaffold($dataModel);
    }

    public function update(Request $request, int|string $id): JsonResponse
    {
        // First process the request data
        $processedRequest = $this->processRequest($request);

        // Then validate the request
        $this->validateUpdateRequest($processedRequest);

        // Finally update the data
        return $this->updateCrud($processedRequest, $id);
    }

    final function updateCrud($request, $id): JsonResponse
    {
        $item = $this->model()->findOrFail($id);
        $item = $this->updateTransaction($request, $item);
        return $this->response()->update($item, $this->model());
    }

    final function updateTransaction($request, $item)
    {
        return DB::transaction(function () use ($request, $item) { // Start the transaction
            $item = $this->updateModel($request, $item);
            DB::commit();
            return $item;
        });
    }

    final function updateModel($request, $item)
    {
        $item->fillable($this->model()->getFillableColumns());

        // Allow hook before updating model data
        $request = $this->callHookIfExists('onBeforeUpdateModel', $request, $item);

        $item->update($request->all());

        // Allow hook after updating the model
        $item = $this->callHookIfExists('onAfterUpdateModel', $item, $request);

        $this->updateRelations($request, $item);

        // Allow hook after everything is updated
        $item = $this->callHookIfExists('onAfterUpdateCompleted', $item, $request);

        return $item;
    }


    final function updateRelations(Request $request, $item): void
    {
        // Allow hook before updating relations
        $this->callHookIfExists('onBeforeUpdateRelations', $item, $request);

        foreach ($this->model()->getAllDataFields() as $field) {
            if (isset($field->relation) && $field->multiple) {
                $data = $request->input($field->name);
                if (isset($data)) {
                    // Allow hook for specific relation
                    $data = $this->callHookIfExists('onBeforeUpdateRelation', $data, $field->name, $item, $request);

                    $item->{$field->relation->name}()->sync($data);

                    // Allow hook after updating specific relation
                    $this->callHookIfExists('onAfterUpdateRelation', $field->name, $item, $request);
                }
            }
        }

        // Allow hook after all relations are updated
        $this->callHookIfExists('onAfterUpdateRelations', $item, $request);
    }
}
