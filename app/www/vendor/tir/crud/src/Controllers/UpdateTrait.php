<?php

namespace Tir\Crud\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Support\Requests\CrudRequest;

trait UpdateTrait
{

    public function update(CrudRequest $request, int|string $id): JsonResponse
    {
        return $this->updateCrud($request, $id);
    }

    final function updateCrud($request, $id): JsonResponse
    {
        $item = $this->model()->findOrFail($id);
        $item = $this->updateTransaction($request, $item);
        return $this->updateResponse($item);
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

        if( !$item->getFillable()){
            $fields = collect($this->model()->getAllDataFields())
                ->pluck('request')->flatten()->unique()->toArray();
            $item->fillable($fields);
        }
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


    final function updateResponse($item): JsonResponse
    {
        $moduleName = $this->model()->getModuleName();
        $message = trans('core::message.item-updated', ['item' => trans("message.item.$moduleName")]); //translate message
        return Response::Json(
            [
                'id'      => $item->id,
                'changes' => $item->getChanges(),
                'updated' => true,
                'message' => $message,
            ]
            , 200);

    }

}
