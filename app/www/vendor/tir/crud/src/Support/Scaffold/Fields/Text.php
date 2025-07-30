<?php

namespace Tir\Crud\Support\Scaffold\Fields;

 use Tir\Crud\Support\Enums\FilterType;

 class Text extends BaseField
{
     protected string $type = 'Text';
     protected array $relation;
     protected FilterType | string $filterType = FilterType::Search;


     public function relation(string $name, string $field, string $primaryKey = 'id'):Text
     {
         $this->relation = ['name' => $name, 'field' => $field, 'key' => $primaryKey];
         return $this;
     }

 }
