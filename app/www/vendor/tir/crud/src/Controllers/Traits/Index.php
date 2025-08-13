<?php

namespace Tir\Crud\Controllers\Traits;

use Tir\Crud\Support\Hooks\IndexHooks;
use Illuminate\Support\Facades\Response;

trait Index
{
    use IndexData;

    public final function index()
    {
        // Access check is now handled automatically in callAction()

        $cols = [];
        $scaffold = $this->scaffolder()->getIndexScaffold();

        // Override actions with access-filtered ones
        $scaffold['configs']['actions'] = $this->getAvailableActions();
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
