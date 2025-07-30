<?php

namespace Tir\Crud\Support\Scaffold\Fields;


class Group extends BaseField
{
    protected string $type = 'Group';
    protected array $subFields = [];
    protected array $children = [];
    protected bool $fillable = false;
    protected bool $requestable = false;


    public function children(...$inputs):Group
    {
        $this->children = $inputs;
        return $this;
    }
    private function getChildren($dataModel): array
    {
        $fields = [];
        foreach ($this->children as $input){
            if($this->readonly){
                $input->readonly();
            }
            $fields[]  = $input->get($dataModel);
        }
        $this->children = $fields;
//        $this->subFields = $fields;
        return $this->children;
    }


    public function get($dataModel)
    {
        $this->getChildren($dataModel);
        return parent::get($dataModel);
    }
}
