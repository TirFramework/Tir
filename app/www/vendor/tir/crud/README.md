# CRUD Package

CRUD package for Tir Framework based on Laravel

## Features

- Trait-based CRUD operations
- Automatic route generation
- Laravel/Tir framework integration
- Comprehensive test coverage

## Testing

The package includes an optimized Docker testing environment with automatic code coverage:

```bash
# Run tests with coverage (recommended)
./test-docker.sh

# Interactive debugging shell
./test-docker.sh interactive

# Clean up Docker resources
./test-docker.sh clean
```

### Coverage Reports

Tests automatically generate comprehensive coverage reports:
- **HTML**: `coverage/html/index.html` - Interactive browsable coverage
- **XML**: `coverage/clover.xml` - For CI/CD integration

### Performance

The Docker setup uses a pre-built image with all dependencies for fast test execution (~1.85 seconds total time).

### Test Results

- **170 tests** with **704 assertions**
- **18.42% line coverage** (344/1868 lines)
- **10.91% method coverage** (37/339 methods)

## Documentation

- [Access Control System](docs/ACCESS_CONTROL.md) - Comprehensive guide to the access control system
- [Testing Documentation](TESTING.md)
