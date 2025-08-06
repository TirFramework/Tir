<?php

namespace Tir\Crud\Support\Hooks;

trait UpdateHooks
{
    use RequestHooks;

    /**
     * Set custom hook for updating model data
     */
    protected function onUpdate(callable $callback): self
    {
        $this->crudHookCallbacks['update'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for handling relationships during update
     */
    protected function onUpdateRelations(callable $callback): self
    {
        $this->crudHookCallbacks['updateRelations'] = $callback;
        return $this;
    }

    /**
     * Set hook for before updating model
     */
    protected function onBeforeUpdateModel(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeUpdateModel'] = $callback;
        return $this;
    }

    /**
     * Set hook for after updating model
     */
    protected function onAfterUpdateModel(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterUpdateModel'] = $callback;
        return $this;
    }

    /**
     * Set hook for before updating relations
     */
    protected function onBeforeUpdateRelations(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeUpdateRelations'] = $callback;
        return $this;
    }

    /**
     * Set hook for before updating a specific relation
     */
    protected function onBeforeUpdateRelation(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeUpdateRelation'] = $callback;
        return $this;
    }

    /**
     * Set hook for after updating a specific relation
     */
    protected function onAfterUpdateRelation(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterUpdateRelation'] = $callback;
        return $this;
    }

    /**
     * Set hook for after updating all relations
     */
    protected function onAfterUpdateRelations(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterUpdateRelations'] = $callback;
        return $this;
    }

    /**
     * Set hook for after update operation is completed
     */
    protected function onAfterUpdateCompleted(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterUpdateCompleted'] = $callback;
        return $this;
    }
}
