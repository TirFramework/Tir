<?php

namespace Tir\Crud\Support\Database\Adapters;

use Illuminate\Database\Connection;
use Tir\Crud\Support\Database\DatabaseAdapterInterface;

/**
 * MySQL Database Adapter
 *
 * Handles MySQL/MariaDB specific operations
 * This is the default/standard SQL adapter
 */
class MySqlAdapter implements DatabaseAdapterInterface
{
    public function getDriverName(): string
    {
        return 'mysql';
    }

    public function supports(Connection $connection): bool
    {
        return in_array($connection->getDriverName(), ['mysql', 'mariadb']);
    }

    public function processRequestData(array $requestData): array
    {
        // MySQL doesn't need special request processing
        return $requestData;
    }

    public function configureRelations($query, $field): mixed
    {
        // Standard SQL relation handling
        if (isset($field->relation)) {
            if ($field->multiple) {
                // Standard many-to-many eager loading
                $query->with($field->relation->name);
            } else {
                // Standard belongs-to eager loading
                $query->with($field->relation->name);
            }
        }

        return $query;
    }

    public function handleManyToManyFilter($query, $field, $value, $model): mixed
    {
        // Get table name from relation
        $table = $model->{$field->relation->name}()->getRelated()->getTable();

        // Get primary key from relation
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
        // Standard SQL date filtering
        $startDate = \Carbon\Carbon::make($dateRange[0])->startOfDay();
        $endDate = \Carbon\Carbon::make($dateRange[1])->endOfDay();

        $query->whereDate($column, '>=', $startDate);
        $query->whereDate($column, '<=', $endDate);

        return $query;
    }

    public function getSelectColumns($model, array $indexFields): array
    {
        // MySQL/SQL: Use table-prefixed column names
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
