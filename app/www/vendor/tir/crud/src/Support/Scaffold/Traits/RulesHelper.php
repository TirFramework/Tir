<?php

namespace Tir\Crud\Support\Scaffold\Traits;

trait RulesHelper
{

    final function getCreationRules(): array
    {
        $rules = [];
        foreach ($this->getAllDataFields() as $field) {
            if ($field->creationRules)
                $rules[$field->name] = $field->creationRules;
        }
        return $rules;
    }

    final function getUpdateRules(): array
    {
        $rules = [];
        foreach ($this->getAllDataFields() as $field) {
            if ($field->updateRules)
                $rules[$field->name] = $field->updateRules;
        }
        return $rules;

    }

    private function getValidationMsg(): array
    {
        return [
            'required' => '${label} is required!',
            'string'   => [
                'max' => '${label} cannot be longer than  ${max} characters'
            ]
        ];
    }


}
