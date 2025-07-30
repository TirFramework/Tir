<?php

namespace Tir\Crud\Support\Scaffold\Fields;

 class Editor extends BaseField
{

   protected string $type = 'Editor';
   protected string $uploadUrl;
   protected string $basePath = '/storage/';
   protected int $height = 400;


    public function height(int $value): Editor
   {
      $this->height = $value;
      return $this;
   }

   public function basePath(string $value): Editor
   {
      $this->basePath = $value;
      return $this;
   }

   public function uploadUrl(string $value): Editor
   {
      $this->uploadUrl = $value;
      return $this;
   }
}
