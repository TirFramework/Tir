<?php

namespace Tir\Crud\Support\Hooks;

trait DestroyHooks
{
    use BaseHooks;

    /**
     * Set custom hook for destroy operation (soft delete)
     */
    protected function onDestroy(callable $callback): self
    {
        $this->crudHookCallbacks['onDestroy'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for destroy response
     */
    protected function onDestroyResponse(callable $callback): self
    {
        $this->crudHookCallbacks['onDestroyResponse'] = $callback;
        return $this;
    }
}
