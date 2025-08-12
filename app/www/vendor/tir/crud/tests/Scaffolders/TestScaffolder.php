<?php

namespace Tir\Crud\Tests\Scaffolders;

use Tir\Crud\Tests\Models\TestModel;

class TestScaffolder
{
    public function model(): string
    {
        return TestModel::class;
    }

    public function fields(): array
    {
        return [
            'name' => [
                'type' => 'text',
                'label' => 'Name',
                'required' => true
            ],
            'email' => [
                'type' => 'email',
                'label' => 'Email',
                'required' => false
            ],
            'description' => [
                'type' => 'textarea',
                'label' => 'Description',
                'required' => false
            ],
            'active' => [
                'type' => 'checkbox',
                'label' => 'Active',
                'default' => true
            ]
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean'
        ];
    }

    public function relations(): array
    {
        return [];
    }

    public function query(): mixed
    {
        return $this->model()::query();
    }

    public function getAllDataFields(): array
    {
        return $this->fields();
    }

    public function getIndexScaffold(): array
    {
        $fields = [];
        foreach ($this->fields() as $name => $field) {
            $fieldObj = (object) array_merge($field, [
                'name' => $name,
                'display' => true,
                'valueType' => $field['type'] ?? 'text',
                'comment' => $field['label'] ?? ucfirst($name),
                'dataSet' => [],
                'relation' => null,
                'filterable' => false,
                'sortable' => true
            ]);
            $fields[] = $fieldObj;
        }

        return [
            'title' => 'Test Index',
            'fields' => $fields,
            'model' => $this->model(),
            'configs' => [
                'pagination' => true,
                'search' => true,
                'export' => false
            ]
        ];
    }

    public function getCreateScaffold(): array
    {
        return [
            'title' => 'Create Test',
            'fields' => $this->fields(),
            'rules' => $this->rules()
        ];
    }

    public function getEditScaffold(): array
    {
        return [
            'title' => 'Edit Test',
            'fields' => $this->fields(),
            'rules' => $this->rules()
        ];
    }

    public function getDetailScaffold(): array
    {
        return [
            'title' => 'Test Details',
            'fields' => $this->fields()
        ];
    }

    public function getModuleName(): string
    {
        return 'test-module';
    }

    public function getUpdateRules(): array
    {
        return $this->rules();
    }

    public function getCreationRules(): array
    {
        return $this->rules();
    }

    public function getActions(): array
    {
        return [
            'create' => true,
            'edit' => true,
            'delete' => true,
            'view' => true
        ];
    }

    public function moduleName(): string
    {
        return 'test-module';
    }

    public function getIndexFields(): array
    {
        $fields = [];
        foreach ($this->fields() as $name => $field) {
            $fieldObj = (object) array_merge($field, [
                'name' => $name,
                'virtual' => false,
                'multiple' => false,
                'relation' => null
            ]);
            $fields[] = $fieldObj;
        }
        return $fields;
    }

    public function getAppendedSelectableColumns(): array
    {
        return [];
    }
}
