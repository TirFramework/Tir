<?php

namespace Tir\Crud\Support\Scaffold\Fields;


class Blank extends BaseField
{
    protected string $type = 'Blank';
    protected bool $fillable = false;
    protected bool $requestable = false;


    public function value(string $value): BaseField
    {
        $this->value = $value;
        return $this;
    }

    protected function setValue($model):void
    {
       //do not set value
    }




}
