# TirAdminKit

Tir is a Laravel-based system that simplifies scaffolding and offers a comprehensive admin panel. It is composed of several packages that work in conjunction to provide a powerful and customizable solution.

![image](https://github.com/TirFramework/Tir/assets/10432008/2a60b3d7-b511-4667-be92-821ae931a1b9)

## Project Overview

### Core Package - CRUD

The "CRUD" package serves as the foundation, handling create, read, update, and delete operations along with validation.

### Mehr-Panel

Mehr-Panel, developed with React.js, complements the "CRUD" package by utilizing JSON templates to create UI elements. It functions as the admin panel, seamlessly integrating with CRUD for efficient data management.

### User Package

The "User" package manages authentication, leveraging CRUD and Mehr-Panel functionalities. It enables user management, and authentication, and interacts with the admin panel.

### Authorization Package

The "Authorization" package dynamically manages access levels, offering granular user permissions for various models and controllers.

### FileManager

The FileManager package handles file operations within the framework.

### Larger Packages (e.g., Blog)

Additional packages, such as the "Blog," utilize functionalities from CRUD, Mehr-Panel, User, and Authorization. They provide specific features like managing blog posts, categories, and their corresponding admin panels.

## Installation

### Prerequisites

- Docker
- PHP Composer

### Installation Steps

1. Clone the main project repository:

    ```bash
    git clone https://github.com/TirFramework/Tir.git
    ```

2. Install dependencies using Composer:

    ```bash
    docker exec tir-app composer install
    ```

3. Start the project using Docker Compose:

    ```bash
    docker-compose -f "docker-compose.local.yml" up -d
    ```

4. Install npm packages for Mehr-Panel:

    ```bash
    docker compose -f "docker-compose.local.yml" run --rm node npm i
    ```

5. Launch the Node.js server for Mehr-Panel:

    ```bash
    docker compose -f "docker-compose.local.yml" run --rm -p "3015:3000" node npm run start
    ```

6. Publish the Mehr-Panel assets:

    ```bash
    php artisan vendor:publish --tag=mehr-panel --force
    ```

Make sure to check port accessibility and set permissions accordingly.

## Contributing

Contributions to the Tir framework are encouraged! Please refer to the contribution guidelines before submitting pull requests or creating issues.

## License

This project is licensed under the [MIT License](LICENSE).
