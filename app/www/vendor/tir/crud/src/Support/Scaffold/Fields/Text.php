<?php

namespace Tir\Crud\Support\Scaffold\Fields;

 use Tir\Crud\Support\Enums\FilterType;

 class Text extends BaseField
{
     protected string $type = 'Text';
     protected FilterType | string $filterType = FilterType::Search;



 }
