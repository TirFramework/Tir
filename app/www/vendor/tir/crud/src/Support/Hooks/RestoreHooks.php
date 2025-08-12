<?php

namespace Tir\Crud\Support\Hooks;

trait RestoreHooks
{
    use BaseHooks;

    /**
     * Set custom hook for restore operation
     */
    protected function onRestore(callable $callback): self
    {
        $this->crudHookCallbacks['onRestore'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for restore response
     */
    protected function onRestoreResponse(callable $callback): self
    {
        $this->crudHookCallbacks['onRestoreResponse'] = $callback;
        return $this;
    }
}
