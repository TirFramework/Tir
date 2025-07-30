<?php

namespace Tir\Crud\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


trait SelectTrait
{


    public function select(request $request): JsonResponse
    {
        $items = [];
        if(isset($request['id']))
        {
            $items =  $this->find($request['id'], $request['field']);
            return Response::Json($items, 200);

        }

        if(isset($request['field']))
        {
            $items = $this->search($request['field'], $request['search']);
            return Response::Json($items, 200);

        }
        return Response::Json($items, 404);

    }

    private function search($field, $search)
    {
        $keyName = $this->model()->getKeyName();
        return  $this->model()::select($keyName.' as value' ,"$field as label")->where($field,'LIKE', '%'.$search.'%')->orderBy('label')->get();

    }

    private function find($id, $field){
        $keyName = $this->model()->getKeyName();
        $id  = explode(',',$id);
        return $this->model()->select($keyName.' as value' ,"$field as label")->whereIn($keyName,$id)->get();

    }



}
