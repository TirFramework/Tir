<?php

namespace Tir\Crud\Support\Hooks;

trait ForceDeleteHooks
{
    use BaseHooks;

    /**
     * Set custom hook for force delete operation
     */
    protected function onForceDelete(callable $callback): self
    {
        $this->crudHookCallbacks['onForceDelete'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for force delete response
     */
    protected function onForceDeleteResponse(callable $callback): self
    {
        $this->crudHookCallbacks['onForceDeleteResponse'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for empty trash operation
     */
    protected function onEmptyTrash(callable $callback): self
    {
        $this->crudHookCallbacks['onEmptyTrash'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for empty trash response
     */
    protected function onEmptyTrashResponse(callable $callback): self
    {
        $this->crudHookCallbacks['onEmptyTrashResponse'] = $callback;
        return $this;
    }
}
