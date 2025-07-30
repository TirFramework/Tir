<?php

namespace Tir\Crud\Support\Scaffold\Fields;


class Link extends BaseField
{
    protected string $type = 'Link';
    protected string $path;
    /**
     * Add display attribute to input
     *
     * @param string $value It will be value attribute of input
     * @return $this
     */
    public function path(string $value): BaseField
    {
        $this->path = $value;
        return $this;
    }


}
