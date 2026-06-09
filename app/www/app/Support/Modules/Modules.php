<?php

namespace App\Support\Modules;

/**
 * Module Registry - Holds all available modules and their permissions
 *
 * Usage:
 * - Register: Modules::register(new Module('user', [...permissions]))
 * - List: Modules::list() - returns all registered modules
 */
class Modules
{
    /**
     * Registry of all registered modules
     *
     * @var array<string, Module>
     */
    private static array $list = [];

    /**
     * Register a new module
     *
     * @param Module $module Module to register
     * @return void
     */
    public static function register(Module $module): void
    {
        self::$list[$module->getName()] = $module;
    }

    /**
     * Get all registered modules
     *
     * @return array<string, Module>
     */
    public static function list(): array
    {
        return self::$list;
    }

    /**
     * Get a specific module by name
     *
     * @param string $name Module name
     * @return Module|null
     */
    public static function get(string $name): ?Module
    {
        return self::$list[$name] ?? null;
    }

    /**
     * Check if a module is registered
     *
     * @param string $name Module name
     * @return bool
     */
    public static function has(string $name): bool
    {
        return isset(self::$list[$name]);
    }

    /**
     * Clear all modules
     *
     * Useful for testing
     *
     * @return void
     */
    public static function clear(): void
    {
        self::$list = [];
    }
}
