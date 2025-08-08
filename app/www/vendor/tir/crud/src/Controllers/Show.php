<?php

namespace Tir\Crud\Controllers;

use Tir\Crud\Support\Hooks\ShowHooks;

trait Show
{
    use ShowHooks;

    public function show(int|string $id)
    {
        // Execute hook for complete override of the show process
        $customResult = $this->callHookIfExists('onShow', $id);
        if ($customResult !== null && $customResult !== $id) {
            return $customResult;
        }

        // Execute hook for retrieving the model (override the query)
        $dataModel = $this->callHookIfExists('onShowGetModel', $id);

        // Check if the hook was actually executed and returned a model
        // If not, or if it just returned the original $id, use the default behavior
        if ($dataModel === $id || $dataModel === null) {
            // Default behavior for retrieving the model
            $dataModel = $this->model()->findOrFail($id);
        }

        // Prepare and return the response
        return $this->scaffolder()->getDetailScaffold($dataModel);
    }
}
