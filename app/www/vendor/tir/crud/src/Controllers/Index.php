<?php

namespace Tir\Crud\Controllers;

use Tir\Crud\Support\Hooks\IndexHooks;
use Illuminate\Support\Facades\Response;

trait Index
{
    use IndexData;

    public final function index()
    {
        $cols = [];
        $scaffold = $this->scaffolder()->getIndexScaffold();
        foreach ($scaffold['fields'] as $index => $field) {
            $cols[$index] = [
                'title'      => $field->display,
                'dataIndex'  => $this->dataIndex($field),
                'fieldName'  => $field->name,
                'valueType'  => $field->valueType,
                'comment'    => $field->comment,
                'dataSet'    => $field->dataSet,
                'dataKey'    => $field->relation->key ?? null,
                'dataField'  => $field->relation->field ?? null,
                'relational' => isset($field->relation),
                'type'       => $field->type,
                'field'      => $field,
            ];
            if($field->filterable){
                $cols[$index]['filters'] = $field->filter;
                $cols[$index]['filterType'] = $field->filterType;
            }
        }

        $data = [
            'actions'    => $this->scaffolder()->getActions(),
            'configs'    => $scaffold['configs'],
            'cols'       => $cols,
            'dataRoute'  => route('admin.' . $this->scaffolder()->moduleName() . '.data'),
            'trashRoute' => route('admin.' . $this->scaffolder()->moduleName() . '.trashData'),
        ];

        return Response::json($data, '200');
    }

    private function dataIndex($field)
    {
        if(isset($field->relation) && $field->multiple){
            return $field->relation->name;
        }else{
            return $field->name;
        }
    }
}
