<?php

namespace Tir\Crud\Support\Scaffold\Fields;


use Tir\Crud\Support\Enums\FilterType;

class Number extends BaseField
{

    protected string $type = 'Number';
    protected  FilterType | string $filterType = FilterType::Slider;
}
