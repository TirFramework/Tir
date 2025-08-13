<?php

namespace Tir\Crud\Controllers\Traits;

use Tir\Crud\Support\Hooks\AccessControlHooks;

trait AccessControl
{
    use AccessControlHooks;

    /**
     * Disable access control for this controller
     * Set to false to disable all access checks
     */
    protected bool $accessControlEnabled = true;

    /**
     * Check if user has access to the given method/action
     * Simple and clear - true = allow, false = deny
     */
    private function checkAccess(string $method): bool
    {
        // Global config override
        if (config('crud.accessLevelControl') === 'off') {
            return true; // Allow all access
        }

        // Controller-level property override
        if (!$this->accessControlEnabled) {
            return true; // Allow all access
        }

        // Custom hook check - allows complete control over access
        $hookResult = $this->callHook('onCheckAccess', $method);
        if ($hookResult !== null) {
            return (bool) $hookResult; // Direct: true = allow, false = deny
        }

        // Default system access check
        try {
            $accessClass = config('crud.access_class', \Tir\Crud\Support\Acl\Access::class);
            $accessInstance = new $accessClass();
            $module = $this->getModuleName();
            return $accessInstance->checkAccess($module, $method);
        } catch (\Exception $e) {
            return false; // Deny access on error
        }
    }

    /**
     * Perform access check and abort if denied
     */
    private function enforceAccess(string $method): void
    {
        if (!$this->checkAccess($method)) {
            abort(403, 'Access denied to ' . $method . ' action');
        }
    }

    /**
     * Get module name for access control
     */
    private function getModuleName(): string
    {
        return $this->scaffolder()->getModuleName();
    }

    /**
     * Get available actions filtered by user access
     * Used when sending actions to frontend
     */
    protected function getAvailableActions(): array
    {
        $businessActions = $this->scaffolder()->getActions();
        $filtered = [];

        foreach ($businessActions as $action => $enabled) {
            if ($enabled) {
                $filtered[$action] = $this->checkAccess($action);
            } else {
                $filtered[$action] = false;
            }
        }

        return $filtered;
    }
}
