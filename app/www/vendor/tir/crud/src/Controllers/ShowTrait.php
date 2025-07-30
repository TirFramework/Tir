<?php

namespace Tir\Crud\Controllers;

trait ShowTrait
{
    public function show(int|string $id)
    {
        $dataModel = $this->model()->findOrFail($id);
        return $dataModel->getDetailScaffold();
    }

}
