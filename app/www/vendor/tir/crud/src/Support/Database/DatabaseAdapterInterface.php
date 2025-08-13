<?php

namespace Tir\Crud\Support\Database;

/**
 * Database Adapter Interface
 *
 * Defines the contract for database-specific operations
 * allowing the CRUD system to work with different database types
 * without scattering database-specific code throughout the system.
 */
interface DatabaseAdapterInterface
{
    /**
     * Get the database driver name
     */
    public function getDriverName(): string;

    /**
     * Check if this adapter supports the given connection
     */
    public function supports(\Illuminate\Database\Connection $connection): bool;

    /**
     * Process request data for this database type
     * Handle any database-specific request transformations
     */
    public function processRequestData(array $requestData): array;

    /**
     * Configure query relations for this database type
     * Handle any database-specific relation logic
     */
    public function configureRelations($query, $field): mixed;

    /**
     * Handle many-to-many relation filtering for this database type
     */
    public function handleManyToManyFilter($query, $field, $value, $model): mixed;

    /**
     * Get the primary key column name for relations
     */
    public function getRelationPrimaryKey($model, $field): string;

    /**
     * Apply date range filter for this database type
     * Handle database-specific date filtering (e.g., MongoDB BSON dates)
     */
    public function applyDateFilter($query, string $column, array $dateRange): mixed;

    /**
     * Get select columns for index queries
     * Handle database-specific column selection logic
     */
    public function getSelectColumns($model, array $indexFields): array;
}
