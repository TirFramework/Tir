<?php

namespace Tir\Crud\Support\Hooks;

trait EditHooks
{
    use BaseHooks;

    /**
     * Set custom hook for edit form generation
     */
    protected function onEdit(callable $callback): self
    {
        $this->crudHookCallbacks['onEdit'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for edit response
     */
    protected function onEditResponse(callable $callback): self
    {
        $this->crudHookCallbacks['onEditResponse'] = $callback;
        return $this;
    }
}
