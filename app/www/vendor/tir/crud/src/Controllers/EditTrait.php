<?php

namespace Tir\Crud\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tir\Crud\Support\Requests\CrudRequest;

trait EditTrait
{
    public function edit(int|string $id)
    {
        $dataModel = $this->model()->findOrFail($id);
        return $this->scaffolder()->getEditScaffold($dataModel);
    }

    public function update(CrudRequest $request, int|string $id): JsonResponse
    {
        return $this->updateCrud($request, $id);
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

        $item->update($request->all());

        $this->updateRelations($request, $item);

        return $item;
    }


    final function updateRelations(Request $request, $item): void
    {
        foreach ($this->model()->getAllDataFields() as $field) {
            if (isset($field->relation) && $field->multiple) {
                $data = $request->input($field->name);
                if (isset($data)) {
                    $item->{$field->relation->name}()->sync($data);
                }
            }
        }
    }
}
