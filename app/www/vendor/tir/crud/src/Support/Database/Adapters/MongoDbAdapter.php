<?php

namespace Tir\Crud\Support\Database\Adapters;

use Illuminate\Database\Connection;
use Illuminate\Support\Arr;
use Tir\Crud\Support\Database\DatabaseAdapterInterface;

/**
 * MongoDB Database Adapter
 *
 * Handles MongoDB specific operations including:
 * - Request data processing (array grouping by numeric indexes)
 * - Relation handling for MongoDB documents
 * - Many-to-many filtering for MongoDB collections
 * - Date filtering with MongoDB BSON types
 */
class MongoDbAdapter implements DatabaseAdapterInterface
{
    public function getDriverName(): string
    {
        return 'mongodb';
    }

    public function supports(Connection $connection): bool
    {
        return $connection->getDriverName() === 'mongodb';
    }

    public function processRequestData(array $requestData): array
    {
        // MongoDB specific: Group array items with numeric indexes
        return $this->groupByNumber($requestData);
    }

    public function configureRelations($query, $field): mixed
    {
        // MongoDB specific relation handling
        if (isset($field->relation)) {
            if ($field->multiple) {
                // MongoDB needs foreign key in many-to-many relation
                $foreignKey = $query->getModel()->{$field->relation->name}()->getForeignKey();
                $otherKey = $query->getModel()->{$field->relation->name}()->getRelated()->getKeyName();

                $query->with([$field->relation->name => function ($q) use ($foreignKey, $otherKey) {
                    // MongoDB specific relation configuration if needed
                }]);
            } else {
                // Standard relation for MongoDB
                $query->with($field->relation->name);
            }
        }

        return $query;
    }

    public function handleManyToManyFilter($query, $field, $value, $model): mixed
    {
        // MongoDB specific many-to-many filtering
        $primaryKey = $model->{$field->relation->name}()->getRelated()->getKeyName();

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
        // MongoDB doesn't need table.column format
        return $model->{$field->relation->name}()->getRelated()->getKeyName();
    }

    /**
     * Apply date filter for MongoDB
     * MongoDB requires special handling for date ranges
     */
    public function applyDateFilter($query, string $column, array $dateRange): mixed
    {
        $startDate = \Carbon\Carbon::make($dateRange[0])->startOfDay();
        $endDate = \Carbon\Carbon::make($dateRange[1])->endOfDay();

        $query->where(function ($query) use ($column, $startDate) {
            // Check if MongoDB BSON class exists (only when MongoDB extension is installed)
            if (class_exists('MongoDB\BSON\UTCDateTime')) {
                $mongoStartDate = new \MongoDB\BSON\UTCDateTime($startDate);
                $query->where($column, '>=', $mongoStartDate);
            }
            $query->orWhere($column, '>=', $startDate->toDateString());
        });

        $query->where(function ($query) use ($column, $endDate) {
            // Check if MongoDB BSON class exists (only when MongoDB extension is installed)
            if (class_exists('MongoDB\BSON\UTCDateTime')) {
                $mongoEndDate = new \MongoDB\BSON\UTCDateTime($endDate);
                $query->where($column, '<=', $mongoEndDate);
            }
            $query->orWhere($column, '<=', $endDate->toDateString());
        });

        return $query;
    }

    public function getSelectColumns($model, array $indexFields): array
    {
        // MongoDB specific: Just get field names without table prefixes
        return collect($indexFields)->pluck('name')->toArray();
    }

    /**
     * Group array items with numeric indexes
     * This is MongoDB specific logic that was scattered in ProcessRequest
     */
    private function groupByNumber(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $parts = preg_split('/\.\d+\./', $key);
            if (count($parts) == 1) {
                $result[$key] = $value;
            } else {
                preg_match('/\.\d+\./', $key, $matches);
                $index = str_replace('.', '', $matches[0] ?? '');
                $prefix = $parts[0] ?? null;
                $suffix = $parts[1] ?? null;

                if ($suffix) {
                    $result[$prefix][$index][$suffix] = $value;
                } else {
                    $result[$prefix][$index] = $value;
                }
            }
        }

        return $result;
    }
}
