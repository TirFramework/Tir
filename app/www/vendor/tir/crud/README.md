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

The Docker setup uses a pre-built image with all dependencies for fast test execution (~6 seconds vs ~20 seconds for installing dependencies each time).

## Documentation

- [Docker Testing Guide](DOCKER-PERFORMANCE.md)
- [Testing Documentation](TESTING.md)