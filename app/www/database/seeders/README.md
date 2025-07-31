# Database Seeding Instructions

This document provides instructions for seeding the database with sample data for both MinimalExample and SampleModel.

## Available Seeders

### 1. MinimalExampleSeeder
Creates sample data for the MinimalExample model demonstrating:
- Auto-fillable generation
- Auto-label functionality
- Virtual fields
- Field validation
- Different data types and edge cases

**Data Created:**
- 10 specific demonstration examples
- 10 random active examples
- 5 inactive examples
- 3 examples with detailed notes
- 2 examples with long titles (validation testing)
- 2 examples with special characters
- 3 examples with unicode characters

**Total: 35 MinimalExample records**

### 2. SampleModelSeeder
Creates comprehensive sample data for the SampleModel demonstrating:
- All field types (21+ different fields)
- Relationships (user associations, parent-child)
- File attachments
- JSON fields
- Different statuses and types

**Data Created:**
- 5 featured items
- 15 published items
- 8 draft items
- 6 premium items
- 3 enterprise items
- Parent-child relationships
- 10 items with file attachments
- 5 archived items
- 3 specific documentation examples

**Total: 50+ SampleModel records**

## Running the Seeders

### Option 1: Run All Seeders
```bash
php artisan db:seed
```
This runs the DatabaseSeeder which includes both MinimalExample and SampleModel seeders.

### Option 2: Run Specific Seeders
```bash
# Run only MinimalExample seeder
php artisan db:seed --class=MinimalExampleSeeder

# Run only SampleModel seeder
php artisan db:seed --class=SampleModelSeeder
```

### Option 3: Fresh Migration + Seed
```bash
# Reset database and run all migrations + seeders
php artisan migrate:fresh --seed
```

## Prerequisites

Before running the seeders, ensure:

1. **Migrations are run:**
   ```bash
   php artisan migrate
   ```

2. **Required tables exist:**
   - `users` table
   - `minimal_examples` table  
   - `sample_models` table

3. **Factories are available:**
   - `UserFactory` (Laravel default)
   - `MinimalExampleFactory` (created)
   - `SampleModelFactory` (already exists)

## Seeder Features

### MinimalExampleSeeder Features:
- ✅ Demonstrates auto-fillable generation
- ✅ Shows auto-label functionality  
- ✅ Tests all field types
- ✅ Includes edge cases and validation testing
- ✅ Creates unicode and special character examples
- ✅ Uses both manual data and factory generation

### SampleModelSeeder Features:
- ✅ Comprehensive field type coverage
- ✅ Relationship demonstrations
- ✅ File attachment examples
- ✅ JSON field usage
- ✅ Multiple status and type variations
- ✅ Parent-child hierarchy examples

## Verification

After seeding, verify the data:

```bash
# Check MinimalExample records
php artisan tinker
>>> App\Models\MinimalExample::count()
>>> App\Models\MinimalExample::where('is_active', true)->count()

# Check SampleModel records  
>>> App\Models\SampleModel::count()
>>> App\Models\SampleModel::where('status', 'published')->count()
```

## Troubleshooting

### Common Issues:

1. **Foreign Key Constraints:**
   - Ensure User records exist before running seeders
   - DatabaseSeeder creates users first, then runs model seeders

2. **Unique Constraint Violations:**
   - Reset database if re-running: `php artisan migrate:fresh --seed`
   - Factories use `unique()` where needed

3. **Memory Issues with Large Datasets:**
   - Seeders are optimized for reasonable data sizes
   - Use `--memory=512M` if needed: `php artisan db:seed --memory=512M`

## Testing the CRUD Operations

After seeding, test the CRUD operations:

```bash
# MinimalExample endpoints
GET    /api/v1/admin/minimal-example
POST   /api/v1/admin/minimal-example
GET    /api/v1/admin/minimal-example/{id}
PUT    /api/v1/admin/minimal-example/{id}
DELETE /api/v1/admin/minimal-example/{id}

# SampleModel endpoints (if configured)
GET    /api/v1/admin/sample-model
# ... etc
```

The seeded data provides a comprehensive testing environment for all framework features!
