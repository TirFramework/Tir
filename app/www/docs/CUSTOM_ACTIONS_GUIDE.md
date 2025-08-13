# Custom Actions Guide

The Tir CRUD system now supports both **predefined enum actions** (with full IDE autocompletion) and **custom user-defined actions** for maximum flexibility.

## Overview

You get the best of both worlds:
- **Type Safety**: Predefined `ActionType` enum cases with IDE autocompletion
- **Extensibility**: Custom string actions for user-specific requirements like `'inline-edit'`, `'bulk-export'`, etc.

## Quick Examples

### Basic Usage

```php
// Mix predefined and custom actions
return Actions::only(
    ActionType::INDEX,      // ✅ Type-safe enum action  
    ActionType::SHOW,       // ✅ Type-safe enum action
    'inline-edit',          // ✅ Custom action
    'bulk-export',          // ✅ Custom action
    'send-notification'     // ✅ Custom action
);
```

### Available Methods

#### 1. `Actions::only()` - Enable Specific Actions
```php
// Supports both enum and string actions
Actions::only(ActionType::INDEX, ActionType::SHOW, 'custom-action')
```

#### 2. `Actions::except()` - Exclude Specific Actions  
```php
// Disable specific actions (works with enum and string)
Actions::except(ActionType::DESTROY, 'dangerous-custom-action')
```

#### 3. `Actions::addCustom()` - Add Custom to Predefined Set
```php
// Start with basic actions, add custom ones
Actions::addCustom(
    Actions::basic(),
    'inline-edit',
    'bulk-export',
    'duplicate-record'
)
```

#### 4. `Actions::mixed()` - Clean Syntax for Mixed Actions
```php
// Separate enum and custom actions clearly
Actions::mixed(
    [ActionType::INDEX, ActionType::EDIT, ActionType::SHOW],
    ['inline-edit', 'quick-duplicate', 'export-pdf']
)
```

#### 5. `Actions::merge()` - Combine Configurations
```php
$base = Actions::readOnly();
$custom = ['inline-edit' => true, 'bulk-delete' => true];
$result = Actions::merge($base, $custom);
```

## Predefined Action Types

### Core CRUD Actions
- `ActionType::INDEX` - List view
- `ActionType::CREATE` - Create new record
- `ActionType::SHOW` - View single record  
- `ActionType::EDIT` - Edit existing record
- `ActionType::DESTROY` - Soft delete (if supported)
- `ActionType::FORCE_DELETE` - Permanent delete
- `ActionType::RESTORE` - Restore soft-deleted record

## Utility Methods

### Check Action Status
```php
// Works with both enum and string actions
Actions::isEnabled($config, ActionType::EDIT)        // true/false
Actions::isEnabled($config, 'inline-edit')           // true/false
```

### Get Enabled Actions
```php
// Returns array of enabled action names (including custom)
$enabled = Actions::getEnabled($config);
// Example output: ['index', 'show', 'inline-edit', 'bulk-export']
```

### Get Enum Actions Only
```php
// Returns array of ActionType enum cases that are enabled
$enumActions = Actions::getEnabledActions($config);
```

## Real-World Examples

### E-commerce Product Management
```php
protected function setActions(): array
{
    return Actions::mixed(
        [ActionType::INDEX, ActionType::CREATE, ActionType::EDIT, ActionType::SHOW],
        [
            'bulk-price-update',
            'inventory-sync', 
            'promote-featured',
            'generate-barcode'
        ]
    );
}
```

### Content Management System
```php
protected function setActions(): array
{
    return Actions::addCustom(
        Actions::except(ActionType::FORCE_DELETE), // Keep all except permanent delete
        'schedule-publish',
        'seo-analysis',
        'duplicate-to-draft',
        'send-for-review'
    );
}
```

### User Management with Custom Workflows
```php
protected function setActions(): array
{
    return Actions::only(
        ActionType::INDEX,
        ActionType::SHOW,
        ActionType::EDIT,
        'send-welcome-email',
        'reset-password',  
        'bulk-role-assignment',
        'export-user-report',
        'trigger-verification'
    );
}
```

### Read-Only with Limited Custom Actions
```php
protected function setActions(): array
{
    return Actions::addCustom(
        Actions::readOnly(),  // Only INDEX and SHOW
        'export-pdf',
        'print-report'
    );
}
```

## Best Practices

### 1. Use Enum Actions When Available
```php
// ✅ Good - Type-safe with autocompletion
ActionType::INDEX, ActionType::CREATE, ActionType::SHOW

// ✅ Custom actions for specific needs  
'export-pdf', 'inline-edit', 'bulk-operations'
```

### 2. Clear Custom Action Names
```php
// ✅ Good - Clear, descriptive names
'bulk-export', 'inline-edit', 'send-notification'

// ❌ Avoid - Unclear or generic
'action1', 'do-something', 'x'
```

### 3. Group Related Actions
```php
// ✅ Good - Logical grouping
Actions::mixed(
    [ActionType::INDEX, ActionType::SHOW, ActionType::EDIT],
    ['inline-edit', 'bulk-edit', 'advanced-edit']
)
```

### 4. Document Custom Actions
```php
protected function setActions(): array
{
    return Actions::only(
        ActionType::INDEX,
        ActionType::SHOW,
        'inline-edit',        // Quick edit without full form
        'bulk-export',        // Export multiple records
        'send-notification'   // Send email to record owner
    );
}
```

## IDE Support

### Autocompletion Works For:
- ✅ `ActionType::` enum cases (full IntelliSense)
- ✅ `Actions::` method names  
- ✅ Method parameters and return types

### Custom Action Benefits:
- ✅ Runtime flexibility for user requirements
- ✅ No need to modify core enum for project-specific actions
- ✅ Easy to add new actions without framework changes

## Migration from Old System

### Before (String-Based)
```php
// Old way - no type safety
return [
    'index' => true,
    'create' => true, 
    'custom-action' => true
];
```

### After (Hybrid Approach)
```php
// New way - type safety + flexibility
return Actions::only(
    ActionType::INDEX,    // Type-safe
    ActionType::CREATE,   // Type-safe  
    'custom-action'       // Custom when needed
);
```

This approach gives you:
- **Type Safety**: IDE autocompletion and error checking for predefined actions
- **Flexibility**: Add any custom actions your application needs
- **Maintainability**: Clear distinction between framework actions and custom ones
- **Future-Proof**: Easy to add new predefined actions without breaking existing custom ones
