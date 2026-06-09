<?php

namespace App\Support\Modules;

/**
 * Module Definition - Represents a single module with its permissions
 *
 * A module can be any CRUD entity (user, category, post, etc.)
 * Each module has a list of available permissions (actions).
 *
 * Example:
 * ```php
 * $module = new Module('user');
 * $module->setPermissions([
 *     ['label' => 'List', 'value' => 'index'],
 *     ['label' => 'View', 'value' => 'show'],
 *     ['label' => 'Create', 'value' => 'create'],
 *     ['label' => 'Edit', 'value' => 'edit'],
 *     ['label' => 'Delete', 'value' => 'destroy'],
 * ]);
 * ```
 */
class Module
{
    /**
     * Module name (e.g., 'user', 'category', 'post')
     *
     * @var string
     */
    private string $name;

    /**
     * Available permissions for this module
     *
     * Format: [
     *     ['label' => 'List Users', 'value' => 'index'],
     *     ['label' => 'Create User', 'value' => 'create'],
     *     ...
     * ]
     *
     * @var array
     */
    private array $permissions = [];

    /**
     * Whether this module is enabled
     *
     * @var bool
     */
    private bool $enabled = true;

    /**
     * Create a new Module
     *
     * @param string $name Module name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Set module name
     *
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get module name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set permissions for this module
     *
     * @param array $permissions Array of permission arrays
     * @return self
     */
    public function setPermissions(array $permissions): self
    {
        $this->permissions = $permissions;
        return $this;
    }

    /**
     * Get permissions for this module
     *
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * Enable this module
     *
     * @return self
     */
    public function enable(): self
    {
        $this->enabled = true;
        return $this;
    }

    /**
     * Disable this module
     *
     * @return self
     */
    public function disable(): self
    {
        $this->enabled = false;
        return $this;
    }

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
