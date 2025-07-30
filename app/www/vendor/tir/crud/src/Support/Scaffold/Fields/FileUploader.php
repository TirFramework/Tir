<?php

namespace Tir\Crud\Support\Scaffold\Fields;


use Illuminate\Support\Arr;

class FileUploader extends BaseField
{

    protected string $type = 'FileUploader';
    protected string $postUrl;
    protected string $basePath;
    protected string $fileRules;
    protected int $maxCount = 1;

    public function uploadUrl($url): static
    {
        $this->postUrl = $url;
        return $this;
    }

    public function basePath($path): static
    {
        $this->basePath = $path;
        return $this;
    }


    public function maxCount(int $value): static
    {
        $this->maxCount = $value;
        return $this;
    }

    public function fileRules($rule)
    {
        $this->fileRules = $rule;
        return $this;
    }
}
