<?php

namespace Tir\Crud\Support\Database\Adapters;

use Illuminate\Database\Connection;
use Tir\Crud\Support\Database\DatabaseAdapterInterface;

/**
 * SQLite Database Adapter
 *
 * Handles SQLite specific operations
 * Most operations are similar to MySQL but with SQLite-specific optimizations
 */
class SqliteAdapter implements DatabaseAdapterInterface
{
    public function getDriverName(): string
    {
        return 'sqlite';
    }

    public function supports(Connection $connection): bool
    {
        return $connection->getDriverName() === 'sqlite';
    }

    public function processRequestData(array $requestData): array
    {
        // SQLite doesn't need special request processing
        return $requestData;
    }

    public function configureRelations($query, $field): mixed
    {
        // Standard SQL relation handling (same as MySQL)
        if (isset($field->relation)) {
            if ($field->multiple) {
                $query->with($field->relation->name);
            } else {
                $query->with($field->relation->name);
            }
        }

        return $query;
    }

    public function handleManyToManyFilter($query, $field, $value, $model): mixed
    {
        // SQLite specific many-to-many filtering (similar to MySQL but optimized for SQLite)
        $table = $model->{$field->relation->name}()->getRelated()->getTable();
        $primaryKey = $model->{$field->relation->name}()->getRelated()->getKeyName();
        $primaryKey = $table . '.' . $primaryKey;

        if (is_array($value)) {
            $query->whereHas($field->relation->name, function ($q) use ($primaryKey, $value) {
                $q->whereIn($primaryKey, $value);
            });
        } else {
            $query->whereHas($field->relation->name, function ($q) use ($primaryKey, $value) {
                $q->where($primaryKey, $value);
            });
        }

        return $query;
    }

    public function getRelationPrimaryKey($model, $field): string
    {
        $table = $model->{$field->relation->name}()->getRelated()->getTable();
        $primaryKey = $model->{$field->relation->name}()->getRelated()->getKeyName();

        return $table . '.' . $primaryKey;
    }

    public function applyDateFilter($query, string $column, array $dateRange): mixed
    {
        // SQLite date filtering (similar to MySQL)
        $startDate = \Carbon\Carbon::make($dateRange[0])->startOfDay();
        $endDate = \Carbon\Carbon::make($dateRange[1])->endOfDay();

        $query->whereDate($column, '>=', $startDate);
        $query->whereDate($column, '<=', $endDate);

        return $query;
    }

    public function getSelectColumns($model, array $indexFields): array
    {
        // SQLite: Same as MySQL (SQL standard)
        $selectFields = [];
        $selectFields[] = $model->getTable() . '.' . $model->getKeyName();

        foreach ($indexFields as $field) {
            // Check if field is many to many relation or not
            if (!$field->virtual) {
                if (!isset($field->relation) || !$field->multiple) {
                    $selectFields[] = $model->getTable() . '.' . $field->name;
                }
            }
        }

        return $selectFields;
    }
}
