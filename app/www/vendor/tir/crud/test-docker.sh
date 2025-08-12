#!/bin/bash

# CRUD Package Test Runner with Docker
#
# Optimized testing with pre-built image and code coverage

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_usage() {
    echo -e "${BLUE}🧪 CRUD Package Test Runner${NC}"
    echo "================================"
    echo ""
    echo "Usage: $0 [OPTION]"
    echo ""
    echo "Options:"
    echo "  test             Run tests with code coverage (default)"
    echo "  interactive      Open interactive shell for debugging"
    echo "  clean            Clean up Docker volumes and images"
    echo "  help             Show this help message"
    echo ""
    echo "Examples:"
    echo "  $0               # Run tests (default action)"
    echo "  $0 test          # Same as above"
    echo "  $0 interactive   # Debug tests interactively"
}

run_tests() {
    echo -e "${GREEN}🚀 Running CRUD tests with coverage...${NC}"
    docker compose -f docker-compose.test.yml run --rm crud-test
    return $?
}

run_interactive() {
    echo -e "${GREEN}� Opening interactive test shell...${NC}"
    docker compose -f docker-compose.test.yml run --rm crud-test-interactive
    return $?
}

clean_docker() {
    echo -e "${YELLOW}🧹 Cleaning up Docker resources...${NC}"

    # Stop and remove containers
    docker compose -f docker-compose.test.yml down --volumes 2>/dev/null || true

    # Remove the test image
    docker rmi crud-test-image:latest 2>/dev/null || true

    # Remove unused volumes
    docker volume prune -f 2>/dev/null || true

    echo -e "${GREEN}✅ Cleanup completed${NC}"
    return 0
}

run_interactive() {
    echo -e "${GREEN}🐚 Opening interactive test shell...${NC}"
    docker compose -f docker-compose.test.yml run --rm crud-test-interactive
    return $?
}

# Main script logic
case "${1:-test}" in
    "test"|"")
        run_tests
        exit $?
        ;;
    "interactive")
        run_interactive
        exit $?
        ;;
    "clean")
        clean_docker
        exit $?
        ;;
    "help"|"-h"|"--help")
        print_usage
        exit 0
        ;;
    *)
        echo -e "${RED}❌ Unknown option: $1${NC}"
        echo ""
        print_usage
        exit 1
        ;;
esac
