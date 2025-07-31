<?php

namespace Tir\Crud\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Support\Requests\CrudRequest;

trait CreateTrait
{

    public function create(): JsonResponse
    {
        $fields = $this->scaffolder()->getCreateScaffold();
        return Response::json($fields, '200');
    }



    public function store(CrudRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->storeCrud($request);
    }

    final function storeCrud($request): \Illuminate\Http\JsonResponse
    {
        $model = $this->storeTransaction($request);
        return $this->response()->store($model);
    }

    final function storeTransaction($request)
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
    final function storeModel($request)
    {
        // Store model
        $this->model()->fillable($this->model()->getFillableColumns());

        $this->model()->fill($request->all());
        $this->model()->save();
        //Store relations
        $this->storeRelations($request);
        return  $this->model();

    }

    final function storeRelations(Request $request): void
    {
        foreach ($this->model()->getAllDataFields() as $field) {
            if (isset($field->relation) && $field->multiple) {
                $data = $request->input($field->name);
                if (isset($data)) {
                    $this->model()->{$field->relation->name}()->sync($data);
                }
            }
        }
    }


}
