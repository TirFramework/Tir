<?php

namespace Tir\Crud\Support\Database;

use Illuminate\Database\Connection;
use Tir\Crud\Support\Database\Adapters\MySqlAdapter;
use Tir\Crud\Support\Database\Adapters\MongoDbAdapter;
use Tir\Crud\Support\Database\Adapters\SqliteAdapter;

/**
 * Database Adapter Factory
 *
 * Creates the appropriate database adapter based on the connection type.
 * This centralizes database-specific logic and makes it easy to add support
 * for new database types without scattering code throughout the system.
 */
class DatabaseAdapterFactory
{
    /**
     * Available database adapters
     */
    private static array $adapters = [
        MySqlAdapter::class,
        MongoDbAdapter::class,
        SqliteAdapter::class,
    ];

    /**
     * Create the appropriate adapter for the given connection
     */
    public static function create(Connection $connection): DatabaseAdapterInterface
    {
        foreach (self::$adapters as $adapterClass) {
            $adapter = new $adapterClass();

            if ($adapter->supports($connection)) {
                return $adapter;
            }
        }

        // Fallback to MySQL adapter for unknown database types
        return new MySqlAdapter();
    }

    /**
     * Register a custom database adapter
     * Allows users to add support for additional database types
     */
    public static function registerAdapter(string $adapterClass): void
    {
        if (!in_array($adapterClass, self::$adapters)) {
            // Add to the beginning so custom adapters take precedence
            array_unshift(self::$adapters, $adapterClass);
        }
    }

    /**
     * Get all registered adapters
     */
    public static function getAdapters(): array
    {
        return self::$adapters;
    }
}
