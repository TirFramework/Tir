<?php

namespace Tir\Crud\Support\Hooks;

trait IndexDataHooks
{
    use \Tir\Crud\Support\Hooks\BaseHooks;



    /**
     * Set custom hook for initializing query
     */
    protected function onInitQuery(callable $callback): self
    {
        $this->crudHookCallbacks['onInitQuery'] = $callback;
        return $this;
    }

    protected function onSelect(callable $callback): self
    {
        $this->crudHookCallbacks['onSelect'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for search
     */
    protected function onSearch(callable $callback): self
    {
        $this->crudHookCallbacks['onSearch'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for filters
     */
    protected function onFilter(callable $callback): self
    {
        $this->crudHookCallbacks['onFilter'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for sort
     */
    protected function onSort(callable $callback): self
    {
        $this->crudHookCallbacks['onSort'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for relations
     */
    protected function onRelation(callable $callback): self
    {
        $this->crudHookCallbacks['onRelation'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for custom query modifications
     */
    protected function onModifyQuery(callable $callback): self
    {
        $this->crudHookCallbacks['onModifyQuery'] = $callback;
        return $this;
    }



    /**
     * Set custom hook for pagination
     */
    protected function onPaginate(callable $callback): self
    {
        $this->crudHookCallbacks['onPaginate'] = $callback;
        return $this;
    }

    /**
     * Set custom hook for index response
     */
    protected function onIndexResponse(callable $callback): self
    {
        $this->crudHookCallbacks['onIndexResponse'] = $callback;
        return $this;
    }



}
