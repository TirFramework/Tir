<?php
namespace Tir\Crud\Support\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class CrudResponse extends Response
{
    public function update($item, $scaffolder): JsonResponse
    {
        $moduleName = $scaffolder->getModuleName();
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


    public function store($model, $scaffolder): \Illuminate\Http\JsonResponse
    {
        $moduleName = $scaffolder->getModuleName();
        $message = trans('core::message.item-created', ['item' => trans("message.item.$moduleName")]); //translate message
        return Response::Json(
            [
                'id'      => $model->id,
                'created' => true,
                'message' => $message,
            ]
            , 200);

    }
}
