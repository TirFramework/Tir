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
    protected function onBeforeValidateCreate(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeValidateCreate'] = $callback;
        return $this;
    }

    /**
     * Set hook for after validation on create
     */
    protected function onAfterValidateCreate(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterValidateCreate'] = $callback;
        return $this;
    }

    /**
     * Set hook for before validation on update
     */
    protected function onBeforeValidateUpdate(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeValidateUpdate'] = $callback;
        return $this;
    }

    /**
     * Set hook for after validation on update
     */
    protected function onAfterValidateUpdate(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterValidateUpdate'] = $callback;
        return $this;
    }

    /**
     * Set hook for when validator is created
     */
    protected function onValidatorCreated(callable $callback): self
    {
        $this->crudHookCallbacks['onValidatorCreated'] = $callback;
        return $this;
    }
}
