<?php

namespace Tir\Crud\Support\Hooks;

trait ShowHooks
{
    use BaseHooks;

    /**
     * Set custom hook for complete override of the show process
     */
    protected function onShow(callable $callback): self
    {
        $this->crudHookCallbacks['onShow'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for show response
     */
    protected function onShowResponse(callable $callback): self
    {
        $this->crudHookCallbacks['onShowResponse'] = $callback;
        return $this;
    }
}
