<?php

namespace Tir\Crud\Support\Scaffold\Fields;

class Custom extends BaseField
{

    protected string $type = 'Custom';

    public function type(string $name): Custom
    {
        $this->type = $name;
        return $this;
    }


}