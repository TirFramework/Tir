<?php

namespace Tir\Crud\Support\Hooks;

trait UpdateHooks
{
    use \Tir\Crud\Support\Hooks\BaseHooks;

    /**
     * Set custom hook for updating model data
     */
    protected function onUpdate(callable $callback): self
    {
        $this->crudHookCallbacks['onUpdate'] = $callback;
        return $this;
    }


    /**
     * Set custom hook for filling model for update
     */
    protected function onFillModelForUpdate(callable $callback): self
    {
        $this->crudHookCallbacks['onFillModelForUpdate'] = $callback;
        return $this;
    }


    /**
     * Set custom hook for updating model
     */
    protected function onUpdateModel(callable $callback): self
    {
        $this->crudHookCallbacks['onUpdateModel'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for updating relations
     */
    protected function onUpdateRelations(callable $callback): self
    {
        $this->crudHookCallbacks['onUpdateRelations'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for updating a specific relation
     */
    protected function onUpdateRelation(callable $callback): self
    {
        $this->crudHookCallbacks['onUpdateRelation'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for after update operation is completed
     */
    protected function onUpdateCompleted(callable $callback): self
    {
        $this->crudHookCallbacks['onUpdateCompleted'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for update response
     */
    protected function onUpdateResponse(callable $callback): self
    {
        $this->crudHookCallbacks['onUpdateResponse'] = $callback;
        return $this;
    }
}
