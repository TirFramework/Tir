<?php

namespace Tir\Crud\Support\Hooks;

trait RequestHooks
{
    use BaseHooks;

    /**
     * Set hook for before processing request
     */
    protected function onBeforeProcessRequest(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeProcessRequest'] = $callback;
        return $this;
    }

    /**
     * Set hook for after processing request
     */
    protected function onAfterProcessRequest(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterProcessRequest'] = $callback;
        return $this;
    }

    /**
     * Set hook for before validation on create
     */
    protected function onBeforeStoreValidation(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeStoreValidation'] = $callback;
        return $this;
    }

    /**
     * Set hook for after validation on create
     */
    protected function onAfterStoreValidation(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterStoreValidation'] = $callback;
        return $this;
    }

    /**
     * Set hook for before validation on update
     */
    protected function onBeforeUpdateValidation(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeValidateUpdate'] = $callback;
        return $this;
    }

    /**
     * Set hook for after validation on update
     */
    protected function onAfterUpdateValidation(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterUpdateValidation'] = $callback;
        return $this;
    }

}
