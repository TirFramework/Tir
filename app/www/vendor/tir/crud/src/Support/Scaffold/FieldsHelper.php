<?php

namespace Tir\Crud\Support\Scaffold;


trait FieldsHelper
{

    private array $indexFields = [];
    private array $editFields = [];

    final function getFields(): array
    {
        $this->scaffold();

        return $this->fields;

    }

    final function getAllFields()
    {
        $fields = $this->getFields();
        $allFields = $this->getAllChildren($fields);
        return collect($allFields)->values()->toArray();
    }

    final function getAllDataFields()
    {
        $fields = $this->getFields();
        $allFields = $this->getAllChildren($fields);
        return collect($allFields)->where('requestable', true)->values()->toArray();
    }


    final function getFillableColumns()
    {
        $modelFillable = $this->getFillable();
        $scafoldFillable =  collect($this->getAllDataFields())->where('fillable',true)->pluck('request')->flatten()->unique()->toArray();
        $fillables = array_merge($scafoldFillable, $modelFillable);
        $modelGaured = $this->getGuarded();
        $finalFillables = array_diff($fillables, $modelGaured);
        return $finalFillables;
    }




    final function getIndexFields(): array
    {
        $allFields = $this->getAllChildren($this->getFields());
        return collect($allFields)->where('showOnIndex')->values()->toArray();
    }

    final function getEditFields(): array
    {
        $fields = $this->getFields();
        return $this->getChildren($fields, 'showOnEditing');
    }

    final function getDetailFields(): array
    {
        $fields = $this->getFields();
        return $this->getChildren($fields, 'showOnDetail');
    }

    final function getCreateFields(): array
    {
        $fields = $this->getFields();
        return $this->getChildren($fields, 'showOnCreating');
    }

    final function getFieldByName($name)
    {
        foreach ($this->getAllFields() as $field) {
            if ($field->name == $name) {
                return $field;
            }
        }
    }

    final function getSearchableFields(): array
    {
        $fields = [];
        foreach ($this->getIndexFields() as $field) {
            if ($field->searchable) {
                $fields[] = $field;
            }
        }
        return $fields;
    }


    private function getSubFields($fields, $allFields)
    {
        foreach ($fields as $field) {
            if (isset($field->children) && !$field->requestable) {
                $allFields = $this->getSubFields($field->subFields, $allFields);
            } elseif ($field->requestable) {
                $allFields[] = $field;
            }
        }
        return $allFields;
    }


    private function getChildren($fields, $page): array
    {
        $allFields = [];
        foreach ($fields as $field) {
            if ($field->{$page}) {
                if (isset($field->children) && $field->type != 'Additional') {
                    $field->children = collect($field->children)->where($page, true)->values()->toArray();
                    $field->children = $this->getChildren($field->children, $page);
                }
                $allFields[] = $field;
            }
        }

        return $allFields;
    }

    private function getAllChildren($fields): array
    {
        $allFields = [];
        foreach ($fields as $field) {
            if (isset($field->children) && $field->type != 'Additional') {
                $children = $field->children;
//                $field->children = [];
                $allFields[] = $field;
                $allFields = array_merge($allFields, $this->getAllChildren($children));
            } else {
                $allFields[] = $field;
            }
        }

        return $allFields;
    }

}
