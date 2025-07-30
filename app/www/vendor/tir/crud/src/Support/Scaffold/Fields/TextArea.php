<?php

namespace Tir\Crud\Support\Scaffold\Fields;

use Tir\Crud\Support\Enums\FilterType;

class TextArea extends BaseField
{
    protected string $type = 'Textarea';
    protected int $row = 5;
    protected FilterType | string $filterType = FilterType::Search;


    public function row($count): TextArea{
        $this->row = $count;
        return $this;
    }
}
