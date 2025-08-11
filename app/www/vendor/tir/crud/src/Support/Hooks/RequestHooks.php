<?php

namespace Tir\Crud\Support\Hooks;

trait RequestHooks
{
    use \Tir\Crud\Support\Hooks\BaseHooks;

    /**
     * Set custom hook for processing request
     */
    protected function onProcessRequest(callable $callback): self
    {
        $this->crudHookCallbacks['onProcessRequest'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for store validation
     */
    protected function onStoreValidation(callable $callback): self
    {
        $this->crudHookCallbacks['onStoreValidation'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for update validation
     */
    protected function onUpdateValidation(callable $callback): self
    {
        $this->crudHookCallbacks['onUpdateValidation'] = $callback;
        return $this;
    }
}
