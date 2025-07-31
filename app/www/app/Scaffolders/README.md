# Scaffolder Guide

This directory contains scaffolder examples demonstrating best practices for the Tir CRUD framework.

## Architecture Overview

The Tir framework uses a clean separation between **Models** (data layer) and **Scaffolders** (UI definition layer):

- **Models**: Handle data persistence, relationships, and business logic
- **Scaffolders**: Define UI fields, validation rules, and presentation logic
- **Controllers**: Orchestrate between models and scaffolders

## UserScaffolder Example

The `UserScaffolder.php` file demonstrates key patterns and approaches:

### 1. Model Access Patterns

#### Magic Method Approach (Laravel-like)
```php
Text::make('name')
    ->showOnEditing(isset($this->name))
```
- **Pros**: Concise, familiar to Laravel developers
- **Cons**: Less explicit, harder for IDE autocomplete
- **Best for**: Senior developers, simple conditions

#### Helper Method Approach (Explicit)
```php
Text::make('email')
    ->showOnEditing($this->hasValue('email'))
```
- **Pros**: Clear intent, IDE-friendly, better for debugging
- **Cons**: Slightly more verbose
- **Best for**: Junior developers, complex logic

#### Available Helper Methods
- `$this->hasValue($property)` - Check if property exists and is set
- `$this->getValue($property, $default)` - Get property value with optional default
- `$this->currentModel()` - Get the current model instance directly

### 2. Field Configuration Best Practices

#### Field Definition Pattern
```php
Text::make('field_name')
    ->display('Human Readable Label')
    ->placeholder('User guidance text')
    ->rules(['validation', 'rules'])
    ->hideFromAll()                    // Start restrictive
    ->showOnEditing($condition)        // Then enable selectively
```

#### Conditional Display Logic
```php
// Show field only when editing and data exists
->showOnEditing(isset($this->name))

// Show field based on model state
->showOnIndex($this->getValue('status') !== 'deleted')

// Hide sensitive fields appropriately
->hideFromIndex()    // Don't show in listings
->hideFromDetail()   // Don't show in detail view
```

### 3. Security Considerations

```php
Password::make('password')
    ->hideFromIndex()    // Never show passwords in listings
    ->hideFromDetail()   // Never show passwords in detail
    ->rules(['required', 'min:8'])  // Enforce strong passwords
```

### 4. Performance Optimization

```php
Text::make('email')
    ->searchable()  // Enable database search
    ->sortable()    // Enable database sorting
```

## Creating New Scaffolders

1. **Extend BaseScaffolder**
```php
class YourScaffolder extends BaseScaffolder
```

2. **Implement Required Methods**
```php
protected function setModel(): string
protected function setFields(): array  
public function setModuleName(): string
```

3. **Optional Customizations**
```php
protected function setActions(): array
protected function setModuleTitle(): string
protected function getValidationMessages(): array
```

## Field Types Available

- `Text::make()` - Text input fields
- `Email::make()` - Email input with validation
- `Password::make()` - Password input (auto-hidden)
- `Select::make()` - Dropdown selections
- `DatePicker::make()` - Date selection
- `Textarea::make()` - Multi-line text
- `Number::make()` - Numeric input
- `Boolean::make()` - Checkbox/toggle
- `File::make()` - File upload
- `Image::make()` - Image upload with preview

## Validation Rules

All Laravel validation rules are supported:
```php
->rules(['required', 'email', 'unique:users,email', 'min:8'])
```

## Advanced Features

### Conditional Logic
```php
->showOnIndex($this->getValue('type') === 'public')
->hideWhenCreating($this->hasValue('auto_generated'))
```

### Custom Data Sources
```php
Select::make('category')
    ->data([
        ['value' => 1, 'label' => 'Category 1'],
        ['value' => 2, 'label' => 'Category 2'],
    ])
```

### Relationships
```php
Select::make('role_id')
    ->relation('role', 'name', 'id')  // relation_name, display_field, key_field
    ->multiple()  // For many-to-many relationships
```

This scaffolder approach provides a clean, maintainable way to define CRUD interfaces while keeping concerns properly separated.
