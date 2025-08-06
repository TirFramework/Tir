<?php

namespace Tir\Crud\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
trait Destroy
{

    public function destroy($id): JsonResponse
    {
        $item = $this->model()->findOrFail($id);

            $item->delete();

        return $this->deleteResponse();

    }



    protected function deleteResponse(): JsonResponse
    {
        $moduleName = $this->model()->getModuleName();
        $message = trans('core::message.item-deleted', ['item' => trans("message.item.$moduleName")]);

        return Response::Json(
            [
                'deleted' => true,
                'message' => $message,
            ]
            , 200);

    }

     public function restore($id): JsonResponse
     {
         $moduleName = $this->model()->getModuleName();
         $item = $this->model()::onlyTrashed()->findOrFail($id);

             $item->restore();
             $message = trans('core::message.item-restored', ['item' => trans("message.item.$moduleName")]);
             return Response::Json(
                 [
                     'deleted' => true,
                     'message' => $message,
                 ]
                 , 200);

     }



}
