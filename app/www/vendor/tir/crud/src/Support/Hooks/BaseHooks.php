<?php

namespace Tir\Crud\Support\Hooks;

trait BaseHooks
{
    /**
     * The hook callbacks storage
     *
     * @var array
     */
    protected array $crudHookCallbacks = [];

    /**
     * Initialize hooks from a CrudHooks object
     */
    protected function crudHooks(CrudHooks $hooks): void
    {
        $this->crudHookCallbacks = $hooks->getHooks();
    }

    /**
     * Check if a hook callback exists and call it
     */
    protected function callHookIfExists(string $hookName, ...$args)
    {

        if (isset($this->crudHookCallbacks[$hookName])) {

            call_user_func_array($this->crudHookCallbacks[$hookName], $args);
        }

        return $args[0] ?? null; // Return first argument if no hook exists
    }

    protected function callHook(string $hookName, ...$args)
    {
        if (isset($this->crudHookCallbacks[$hookName])) {
            return call_user_func_array($this->crudHookCallbacks[$hookName], $args);
        }
        return null; // Return first argument if no hook exists
    }
}
