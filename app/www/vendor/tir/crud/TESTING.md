# CRUD Package Testing

This document explains how to test the CRUD package.

## Quick Start

The easiest way to run tests is using the Docker test runner:

```bash
# Run all tests with code coverage
./test-docker.sh

# Interactive debugging shell
./test-docker.sh interactive

# Clean up Docker resources
./test-docker.sh clean
```

## Test Results

The test suite includes:
- **170 tests** with **704 assertions**
- **18.42% line coverage** (344/1868 lines)
- **10.91% method coverage** (37/339 methods)
- Execution time: ~1.85 seconds

## Test Structure

### Unit Tests (`tests/Unit/`)
- **Controllers/**: Tests for CRUD controller traits
- **Services/**: Tests for DataService, StoreService, UpdateService
- **Fields/**: Tests for field system components
- **Scaffold/**: Tests for scaffolding system

### Integration Tests (`tests/Integration/`)
- **ControllerMethodExecutionTest.php**: Tests controller method execution
- **ServiceExecutionTest.php**: Tests service layer business logic
- **FieldComprehensiveTest.php**: Tests field system functionality
- **HookSystemTest.php**: Tests hook system integration
- **ScaffolderEdgeCasesTest.php**: Tests edge cases and error handling

### Feature Tests (`tests/Feature/`)
- **CrudEndpointsTest.php**: Tests HTTP endpoints functionality

## Coverage Reports

After running tests, coverage reports are generated:
- **HTML Report**: `coverage/html/index.html`
- **XML Report**: `coverage/clover.xml`
- **Text Report**: `coverage.txt`

## Docker Setup

The testing uses an optimized Docker setup:
- **`test-docker.sh`**: Main test runner script
- **`docker-compose.test.yml`**: Docker Compose configuration
- **`Dockerfile.test`**: Optimized test image with pre-installed dependencies
- **Performance**: Sub-2-second test execution with code coverage

### Feature Tests (`tests/Feature/`)
- **CrudEndpointsTest.php**: End-to-end tests for all CRUD HTTP endpoints

### Test Models (`tests/Models/`)
- **TestModel.php**: Simple model for testing CRUD operations
- **TestCategory.php**: Additional model for relationship testing

### Test Controllers (`tests/Controllers/`)
- **TestController.php**: Test controller that uses the CRUD trait

### Test Scaffolders (`tests/Scaffolders/`)
- **TestScaffolder.php**: Scaffolder for test models

## Test Coverage

The test suite covers:

### ✅ Core Functionality
- **Model Operations**: Create, read, update, delete with database persistence
- **Soft Delete System**: Soft deletion, restoration, and force deletion
- **Class Structure**: CRUD trait composition and controller functionality
- **Scaffolder Integration**: Model binding and field configuration

### ✅ Database Operations
- **CRUD Operations**: Full create, read, update, delete cycle
- **Soft Delete Lifecycle**: Delete → Restore → Force Delete
- **Data Integrity**: Database constraints and relationships
- **Migration Support**: Automated table creation for testing

### ✅ Package Components
- **Trait System**: Verification of all CRUD trait functionality  
- **Hook Architecture**: Hook trait existence and registration methods
- **Service Classes**: DataService and other core services
- **Model Integration**: Eloquent model compatibility

### ✅ Integration Testing
- **Database Persistence**: Real database operations with SQLite
- **Model Relationships**: Testing model associations and queries
- **Scaffolder Configuration**: Field definitions and validation rules
- **Package Loading**: Service provider and autoloading verification

## Running Specific Tests

### Run only Unit tests:
```bash
./vendor/bin/phpunit tests/Unit
```

### Run only Feature tests:
```bash
./vendor/bin/phpunit tests/Feature
```

### Run specific test class:
```bash
./vendor/bin/phpunit tests/Unit/CrudControllerTest.php
```

### Run specific test method:
```bash
./vendor/bin/phpunit --filter test_store_endpoint_creates_new_record
```

## Test Configuration

The test suite uses:
- **SQLite in-memory database** for fast, isolated testing
- **Orchestra Testbench** for Laravel package testing environment
- **PHPUnit 10** as the testing framework
- **Database migrations** automatically created for each test
- **Simplified approach** focusing on core functionality verification

## Writing New Tests

### For new CRUD operations:
1. Add unit tests to verify the method exists and works
2. Add feature tests to verify HTTP endpoints work
3. Add database assertions to verify data changes

### For new hook functionality:
1. Add tests to `HooksTest.php` to verify hook methods exist
2. Add integration tests to verify hooks are called correctly

### Example test for new functionality:
```php
public function test_new_crud_method()
{
    // Arrange: Set up test data
    $model = TestModel::create(['name' => 'Test']);
    
    // Act: Call your method
    $response = $this->post("/test/{$model->id}/new-action");
    
    // Assert: Verify results
    $response->assertStatus(200);
    $this->assertDatabaseHas('test_models', [
        'id' => $model->id,
        'expected_field' => 'expected_value'
    ]);
}
```

## Continuous Integration

To integrate with CI/CD:

```yaml
# .github/workflows/test.yml (for GitHub Actions)
name: Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: shivammathur/setup-php@v2
        with:
          php-version: 8.1
      - run: composer install --dev
      - run: ./vendor/bin/phpunit
```
