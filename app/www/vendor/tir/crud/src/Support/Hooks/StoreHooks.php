<?php

namespace Tir\Crud\Support\Hooks;

trait StoreHooks
{
    use RequestHooks;

    /**
     * Set custom hook for storing model data
     */
    protected function onStore(callable $callback): self
    {
        $this->crudHookCallbacks['store'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for handling relationships during store
     */
    protected function onStoreRelations(callable $callback): self
    {
        $this->crudHookCallbacks['storeRelations'] = $callback;
        return $this;
    }

    /**
     * Set hook for before filling model with data
     */
    protected function onBeforeFillModel(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeFillModel'] = $callback;
        return $this;
    }

    /**
     * Set hook for before saving model
     */
    protected function onBeforeSaveModel(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeSaveModel'] = $callback;
        return $this;
    }

    /**
     * Set hook for after saving model
     */
    protected function onAfterSaveModel(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterSaveModel'] = $callback;
        return $this;
    }

    /**
     * Set hook for before storing relations
     */
    protected function onBeforeStoreRelations(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeStoreRelations'] = $callback;
        return $this;
    }

    /**
     * Set hook for before storing a specific relation
     */
    protected function onBeforeStoreRelation(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeStoreRelation'] = $callback;
        return $this;
    }

    /**
     * Set hook for after storing a specific relation
     */
    protected function onAfterStoreRelation(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterStoreRelation'] = $callback;
        return $this;
    }

    /**
     * Set hook for after storing all relations
     */
    protected function onAfterStoreRelations(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterStoreRelations'] = $callback;
        return $this;
    }

    /**
     * Set hook for after store operation is completed
     */
    protected function onAfterStoreCompleted(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterStoreCompleted'] = $callback;
        return $this;
    }
}
