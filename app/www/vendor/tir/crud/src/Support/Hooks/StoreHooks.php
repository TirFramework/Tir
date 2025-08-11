<?php

namespace Tir\Crud\Support\Hooks;

trait StoreHooks
{
    use \Tir\Crud\Support\Hooks\BaseHooks;

    /**
     * Set custom hook for storing model data
     */
    protected function onStore(callable $callback): self
    {
        $this->crudHookCallbacks['onStore'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for saving model
     */
    protected function onSaveModel(callable $callback): self
    {
        $this->crudHookCallbacks['onSaveModel'] = $callback;
        return $this;
    }


    /**
     * Set custom hook for filling model for store
     */
    protected function onFillModelForStore(callable $callback): self
    {
        $this->crudHookCallbacks['onFillModelForStore'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for storing relations
     */
    protected function onStoreRelations(callable $callback): self
    {
        $this->crudHookCallbacks['onStoreRelations'] = $callback;
        return $this;
    }


    /**
     * Set custom hook for after store operation is completed
     */
    protected function onStoreCompleted(callable $callback): self
    {
        $this->crudHookCallbacks['onStoreCompleted'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for store response
     */
    protected function onStoreResponse(callable $callback): self
    {
        $this->crudHookCallbacks['onStoreResponse'] = $callback;
        return $this;
    }
}
