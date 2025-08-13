<?php

namespace Tir\Crud\Controllers\Traits;

use Tir\Crud\Support\Scaffold\Actions;

/**
 * ActionValidation Trait
 *
 * Provides action validation functionality for CRUD controllers.
 * This trait can be used by any CRUD trait that needs to validate actions.
 */
trait ActionValidation
{
    /**
     * Simple helper to check if an action is enabled
     * Call this at the beginning of each CRUD method
     *
     * @param string $action The action to check (e.g., 'index', 'create', 'edit', 'delete')
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function checkAction(string $action): void
    {
        $enabledActions = $this->scaffolder()->getActions();

        if (!Actions::isEnabled($enabledActions, $action)) {
            $message = "Action '{$action}' is disabled for this resource.";

            if (request()->expectsJson()) {
                abort(403, json_encode([
                    'error' => 'Action Disabled',
                    'message' => $message,
                    'action' => $action
                ]));
            }

            abort(403, $message);
        }
    }

    /**
     * Automatically check action based on calling method name
     * This provides seamless protection for custom methods
     * 
     * Usage: Just call $this->autoCheckAction() at the start of any custom method
     * Method name will be automatically used as action name
     *
     * @param string|null $customAction Override the auto-detected action name
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function autoCheckAction(?string $customAction = null): void
    {
        // Get the calling method name
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $callingMethod = $backtrace[1]['function'] ?? 'unknown';
        
        // Use custom action name or auto-detect from method name
        $action = $customAction ?? $callingMethod;
        
    }

    /**
     * Check if an action is enabled without throwing an exception
     *
     * @param string $action The action to check
     * @return bool True if action is enabled, false otherwise
     */
    protected function isActionEnabled(string $action): bool
    {
        $enabledActions = $this->scaffolder()->getActions();
        return Actions::isEnabled($enabledActions, $action);
    }

    /**
     * Get all enabled actions for this resource
     *
     * @return array Array of enabled actions
     */
    protected function getEnabledActions(): array
    {
        return Actions::getEnabled($this->scaffolder()->getActions());
    }
}
