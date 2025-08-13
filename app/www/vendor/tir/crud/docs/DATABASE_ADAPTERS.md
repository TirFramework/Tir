# Database Adapter System

The Tir CRUD system now uses a **Database Adapter Pattern** to handle database-specific operations cleanly and efficiently. This eliminates scattered database-specific code throughout the system.

## Problem Solved

**Before (Scattered Code):**
```php
// In ProcessRequest.php
if ($this->model->getConnection()->getDriverName() === 'mongodb') {
    // MongoDB specific logic
}

// In DataService.php  
if ($this->model()->getConnection()->getName() == 'mongodb') {
    // More MongoDB specific logic
}

// In many other places...
```

**After (Clean Adapter Pattern):**
```php
// Clean, centralized approach
$adapter = DatabaseAdapterFactory::create($this->model->getConnection());
$processedData = $adapter->processRequestData($requestData);
```

## Available Adapters

### Built-in Adapters

1. **MySqlAdapter** - Handles MySQL/MariaDB databases (default)
2. **MongoDbAdapter** - Handles MongoDB specific operations
3. **SqliteAdapter** - Handles SQLite databases

### Adapter Capabilities

Each adapter handles:

- **Request Processing**: Database-specific request data transformations
- **Relation Configuration**: Database-specific relation handling  
- **Many-to-Many Filtering**: Database-specific filtering logic
- **Date Filtering**: Database-specific date range handling
- **Column Selection**: Database-specific column selection logic

## How It Works

### 1. Automatic Detection

The system automatically detects your database type and uses the appropriate adapter:

```php
// Automatically chooses the right adapter
$adapter = DatabaseAdapterFactory::create($model->getConnection());
```

### 2. Database-Specific Operations

**MongoDB Example:**
```php
// MongoDB requires special array grouping for requests
public function processRequestData(array $requestData): array
{
    return $this->groupByNumber($requestData); // MongoDB specific
}
```

**MySQL Example:**
```php
// MySQL doesn't need special processing
public function processRequestData(array $requestData): array
{
    return $requestData; // Pass through
}
```

## Usage Examples

### Request Processing

```php
// Old way (scattered)
if ($this->model->getConnection()->getDriverName() === 'mongodb') {
    $request->merge($this->groupByNumber($requestTemp));
}

// New way (clean)
$adapter = DatabaseAdapterFactory::create($this->model->getConnection());
$processedData = $adapter->processRequestData($request->all());
$request->merge($processedData);
```

### Date Filtering

```php
// Old way (scattered)
if ($this->model()->getConnection()->getName() == 'mongodb') {
    $query->where($column, '>=', new \MongoDB\BSON\UTCDateTime($startDate));
} else {
    $query->whereDate($column, '>=', $startDate);
}

// New way (clean)
$adapter = DatabaseAdapterFactory::create($this->model()->getConnection());
$query = $adapter->applyDateFilter($query, $column, $dateRange);
```

## Creating Custom Adapters

### 1. Create Adapter Class

```php
<?php

namespace App\Database\Adapters;

use Tir\Crud\Support\Database\DatabaseAdapterInterface;

class PostgreSqlAdapter implements DatabaseAdapterInterface
{
    public function getDriverName(): string
    {
        return 'pgsql';
    }

    public function supports(\Illuminate\Database\Connection $connection): bool
    {
        return $connection->getDriverName() === 'pgsql';
    }

    public function processRequestData(array $requestData): array
    {
        // PostgreSQL specific request processing
        return $requestData;
    }

    public function configureRelations($query, $field): mixed
    {
        // PostgreSQL specific relation configuration
        return $query;
    }

    public function handleManyToManyFilter($query, $field, $value, $model): mixed
    {
        // PostgreSQL specific many-to-many filtering
        return $query;
    }

    public function getRelationPrimaryKey($model, $field): string
    {
        // PostgreSQL specific primary key handling
        $table = $model->{$field->relation->name}()->getRelated()->getTable();
        $primaryKey = $model->{$field->relation->name}()->getRelated()->getKeyName();
        return $table . '.' . $primaryKey;
    }

    public function applyDateFilter($query, string $column, array $dateRange): mixed
    {
        // PostgreSQL specific date filtering
        $startDate = \Carbon\Carbon::make($dateRange[0])->startOfDay();
        $endDate = \Carbon\Carbon::make($dateRange[1])->endOfDay();
        
        $query->whereDate($column, '>=', $startDate);
        $query->whereDate($column, '<=', $endDate);

        return $query;
    }

    public function getSelectColumns($model, array $indexFields): array
    {
        // PostgreSQL specific column selection
        $selectFields = [];
        $selectFields[] = $model->getTable() . '.' . $model->getKeyName();
        
        foreach ($indexFields as $field) {
            if (!$field->virtual && (!isset($field->relation) || !$field->multiple)) {
                $selectFields[] = $model->getTable() . '.' . $field->name;
            }
        }
        
        return $selectFields;
    }
}
```

### 2. Register Custom Adapter

```php
// In a service provider or bootstrap file
use Tir\Crud\Support\Database\DatabaseAdapterFactory;
use App\Database\Adapters\PostgreSqlAdapter;

DatabaseAdapterFactory::registerAdapter(PostgreSqlAdapter::class);
```

## Benefits

### 1. **Clean Architecture**
- Database-specific logic is centralized in adapters
- No more scattered `if (driver === 'mongodb')` checks
- Easier to maintain and understand

### 2. **Extensible**
- Easy to add support for new databases
- Custom adapters can be registered
- Framework remains database-agnostic

### 3. **Testable**
- Each adapter can be tested independently
- Mock adapters for testing
- Clear separation of concerns

### 4. **Performance**
- Database-specific optimizations in dedicated adapters
- No unnecessary checks for unsupported databases
- Efficient adapter selection

## Migration Guide

### From Old Pattern

```php
// Old scattered approach
if ($this->model->getConnection()->getDriverName() === 'mongodb') {
    // MongoDB specific code
} else {
    // SQL specific code
}
```

### To New Adapter Pattern

```php
// New clean approach
$adapter = DatabaseAdapterFactory::create($this->model->getConnection());
$result = $adapter->someMethod($parameters);
```

## Future Enhancements

The adapter system can be extended to handle:

- **Query Optimization**: Database-specific query optimizations
- **Schema Operations**: Database-specific schema handling
- **Index Management**: Database-specific indexing strategies
- **Transaction Handling**: Database-specific transaction logic
- **Backup/Restore**: Database-specific backup operations

This pattern provides a solid foundation for supporting any database type while keeping the codebase clean and maintainable.
