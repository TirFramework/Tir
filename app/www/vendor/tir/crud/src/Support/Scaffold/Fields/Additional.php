<?php

namespace Tir\Crud\Support\Scaffold\Fields;


use Illuminate\Support\Arr;

class Additional extends BaseField
{
    protected string $type = 'Additional';
    protected array $children = [];
    protected array $template = [];
    protected bool $defaultChild = false;

    public function defaultChild($status = true):Additional {
        $this->defaultChild = $status;
        return $this;
    }
    public function children(...$inputs):Additional
    {
        $this->children = $inputs;
        return $this;
    }

    public function request(...$schema): Additional
    {
        $this->request = $schema;
        return $this;
    }

    private function getChildren($dataModel): array
    {
        return $this->getChildrenWithValue($dataModel);
    }


    protected function getChildrenWithValue($model): array
    {
        $fields = [];
        $values = [];
        if(Arr::get($model, $this->name) == null || Arr::get($model, $this->name) == [] ){
            $values = [0];
        }else{
            $values = Arr::get($model, $this->name) ?? [0];
        }

        $index = 0;
        foreach ($values as $value) {
            foreach ($this->children as $field) {
                $field->name = str_replace('*', $index, $field->originalName);
                if($this->readonly){
                    $field->readonly();
                }
                $fields[$index][] = $field->get($model);
                unset($field->value);
            }
            $index++;
        }

        return $fields;

    }


    public function get($dataModel)
    {
        $this->children = $this->getChildren($dataModel);
        $this->template = $this->children[0];
        if(!Arr::get($dataModel, $this->name) && !$this->defaultChild){
            $this->children = [];
        }
        return parent::get($dataModel);
    }

}

