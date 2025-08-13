<?php

namespace Tir\Crud\Support\Hooks;

trait AccessControlHooks
{
    use BaseHooks;

    /**
     * Hook to provide custom access control logic
     * Return true to allow access, false to deny, null for default behavior
     *
     * Example:
     * $this->onCheckAccess(function ($action) {
     *     if ($action === 'destroy') {
     *         return auth()->user()->isAdmin(); // Only admins can delete
     *     }
     *     return true; // Allow all other actions
     * });
     */
    protected function onCheckAccess(callable $callback): self
    {
        $this->crudHookCallbacks['onCheckAccess'] = $callback;
        return $this;
    }
}
