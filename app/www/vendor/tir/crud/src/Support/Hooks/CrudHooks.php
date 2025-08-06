<?php

namespace Tir\Crud\Support\Hooks;

/**
 * Class for managing hook callbacks
 */
class CrudHooks
{
    /**
     * Array of hook callbacks
     *
     * @var array
     */
    protected array $hooks = [];

    /**
     * Set a hook callback
     */
    public function setHook(string $name, callable $callback): self
    {
        $this->hooks[$name] = $callback;
        return $this;
    }

    /**
     * Get all hooks
     */
    public function getHooks(): array
    {
        return $this->hooks;
    }

    /**
     * Check if a hook exists
     */
    public function hasHook(string $name): bool
    {
        return isset($this->hooks[$name]);
    }

    /**
     * Call a hook if it exists
     */
    public function callHook(string $name, ...$args)
    {
        if ($this->hasHook($name)) {
            return call_user_func_array($this->hooks[$name], $args);
        }
        return $args[0] ?? null;
    }
}
