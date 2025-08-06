<?php

namespace Tir\Crud\Support\Hooks;

trait HasCrudHooks
{
    use BaseHooks;

    /**
     * Set custom initQuery hook
     */
    protected function onInitQuery(callable $callback): self
    {
        $this->crudHookCallbacks['modifyInitQuery'] = $callback;
        return $this;
    }

    /**
     * Set custom search hook
     */
    protected function onSearch(callable $callback): self
    {
        $this->crudHookCallbacks['modifySearch'] = $callback;
        return $this;
    }

    /**
     * Set custom filters hook
     */
    protected function onFilters(callable $callback): self
    {
        $this->crudHookCallbacks['modifyFilters'] = $callback;
        return $this;
    }

    /**
     * Set custom sort hook
     */
    protected function onSort(callable $callback): self
    {
        $this->crudHookCallbacks['modifySort'] = $callback;
        return $this;
    }

    /**
     * Set custom relations hook
     */
    protected function onRelations(callable $callback): self
    {
        $this->crudHookCallbacks['modifyRelations'] = $callback;
        return $this;
    }

    /**
     * Set custom columns selection hook
     */
    protected function onColumns(callable $callback): self
    {
        $this->crudHookCallbacks['modifyColumns'] = $callback;
        return $this;
    }

    /**
     * Set custom pagination hook
     */
    protected function onPaginate(callable $callback): self
    {
        $this->crudHookCallbacks['modifyPaginate'] = $callback;
        return $this;
    }

    /**
     * Set custom pagination hook (alias for onPaginate for backward compatibility)
     */
    protected function onPagination(callable $callback): self
    {
        return $this->onPaginate($callback);
    }

    /**
     * Set custom hook for before query execution
     */
    protected function onBeforeExecuteQuery(callable $callback): self
    {
        $this->crudHookCallbacks['onBeforeExecuteQuery'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for after query execution
     */
    protected function onAfterExecuteQuery(callable $callback): self
    {
        $this->crudHookCallbacks['onAfterExecuteQuery'] = $callback;
        return $this;
    }

    /**
     * Set custom with hook
     */
    protected function onWith(callable $callback): self
    {
        $this->crudHookCallbacks['modifyWith'] = $callback;
        return $this;
    }

    /**
     * Set custom columns selection hook
     */
    protected function onSelect(callable $callback): self
    {
        $this->crudHookCallbacks['modifySelect'] = $callback;
        return $this;
    }
}
