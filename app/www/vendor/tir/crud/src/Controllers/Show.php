<?php

namespace Tir\Crud\Controllers;

trait Show
{
    public function show(int|string $id)
    {
        $dataModel = $this->model()->findOrFail($id);
        return $dataModel->getDetailScaffold($dataModel);
    }

}
