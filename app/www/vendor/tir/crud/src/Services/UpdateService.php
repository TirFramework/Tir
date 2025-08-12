<?php

namespace Tir\Crud\Services;

use Tir\Crud\Support\Hooks\UpdateHooks;

class UpdateService
{
    use UpdateHooks;

    private $scaffolder;
    private $model;

    public function __construct($scaffolder, $model)
    {
        $this->scaffolder = $scaffolder;
        $this->model = $model;
    }

    public function setHooks($hooks)
    {
        $this->crudHookCallbacks = $hooks;
    }

    public function edit($id)
    {
        // Define the default behavior as a closure
        $defaultEdit = function($modelId = null) use ($id) {
            if ($modelId !== null) {
                $id = $modelId;
            }
            return $this->model->findOrFail($id);
        };

        // Pass the closure to the hook
        $customEdit = $this->callHook('onUpdate', $defaultEdit, $id);
        if($customEdit !== null) {
            $dataModel = $customEdit;
        } else {
            $dataModel = $defaultEdit();
        }

        // Generate scaffold for the model
        return $this->scaffolder->getEditScaffold($dataModel);
    }
}
