<?php

namespace Tir\Crud\Support\Hooks;

trait CreateHooks
{
    use BaseHooks;

    /**
     * Set custom hook for create form generation
     */
    protected function onCreate(callable $callback): self
    {
        $this->crudHookCallbacks['onCreate'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for create response
     */
    protected function onCreateResponse(callable $callback): self
    {
        $this->crudHookCallbacks['onCreateResponse'] = $callback;
        return $this;
    }
}
