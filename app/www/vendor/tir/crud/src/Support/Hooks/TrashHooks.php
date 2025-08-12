<?php

namespace Tir\Crud\Support\Hooks;

trait TrashHooks
{
    use BaseHooks;

    /**
     * Set custom hook for trash data retrieval
     */
    protected function onTrash(callable $callback): self
    {
        $this->crudHookCallbacks['onTrash'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for trash response
     */
    protected function onTrashResponse(callable $callback): self
    {
        $this->crudHookCallbacks['onTrashResponse'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for trash select query
     */
    protected function onTrashSelect(callable $callback): self
    {
        $this->crudHookCallbacks['onTrashSelect'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for trash filter
     */
    protected function onTrashFilter(callable $callback): self
    {
        $this->crudHookCallbacks['onTrashFilter'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for trash sort
     */
    protected function onTrashSort(callable $callback): self
    {
        $this->crudHookCallbacks['onTrashSort'] = $callback;
        return $this;
    }
}
