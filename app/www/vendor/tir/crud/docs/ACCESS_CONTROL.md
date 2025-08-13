# Access Control Documentation

## Overview

The CRUD framework includes a powerful, flexible access control system that allows you to control user permissions at both the method level and UI level. The system follows a "secure by default" approach while remaining completely optional and easy to override.

## Basic Usage

### 1. Completely Disable Access Control

To disable access control for an entire controller:

```php
<?php

class PublicController extends Controller
{
    use Crud;
    
    // Disable all access control for this controller
    protected $accessControlEnabled = false;
    
    protected function setScaffolder(): string
    {
        return PublicScaffolder::class;
    }
}
```

### 2. Granular Method Control

For fine-grained control over specific methods, use the `onAccessCheck` hook in your `setup()` method:

```php
<?php

class UserController extends Controller
{
    use Crud;
    
    protected function setScaffolder(): string
    {
        return UserScaffolder::class;
    }
    
    protected function setup()
    {
        // Control access for specific methods
        $this->onAccessCheck(function($method) {
            // Make certain methods public (no access check)
            if (in_array($method, ['publicProfile', 'contactInfo'])) {
                return false; // Skip access check
            }
            
            // Force access check for admin methods
            if (in_array($method, ['destroy', 'forceDelete'])) {
                return true; // Require access check
            }
            
            // Use default behavior for other methods
            return null;
        });
        
        // Your other hooks...
        $this->onCreate(function($default) {
            // ... other setup
        });
    }
}
```

## Configuration

### Global Configuration

In your `config/crud.php` file:

```php
return [
    // Completely disable access control globally
    'accessLevelControl' => 'off', // 'off' or 'on'
    
    // Custom access control class (optional)
    'access_class' => App\CustomAccess::class,
];
```

### User Permissions Structure

The system expects user permissions in this format:

```php
// In your User model or authentication system
Auth::user()->permissions = [
    'users' => [
        'index' => true,    // Can view users list
        'create' => false,  // Cannot create users
        'edit' => true,     // Can edit users
        'destroy' => false, // Cannot delete users
    ],
    'reports' => [
        'index' => true,
        'create' => true,
        'edit' => true,
        'destroy' => false,
    ],
];
```

## How It Works

### 1. Method-Level Protection

Every controller method is automatically protected:

```php
// When user calls /users/create
// 1. System checks: config('crud.accessLevelControl') !== 'off'
// 2. System checks: $accessControlEnabled property
// 3. System calls: onAccessCheck hook
// 4. System checks: user permissions for 'users.create'
// 5. If any check fails: throws 403 Forbidden
// 6. If all pass: method executes normally
```

### 2. UI-Level Filtering

Actions sent to the frontend are automatically filtered:

```php
// In your scaffolder, you might define:
'actions' => [
    'create' => true,
    'edit' => true,
    'destroy' => true,
]

// But user only sees what they have access to:
// Frontend receives:
'actions' => [
    'create' => false,  // Button hidden
    'edit' => true,     // Button shown
    'destroy' => false, // Button hidden
]
```

## Advanced Usage

### Custom Access Control Class

Create your own access control logic:

```php
<?php

namespace App\Security;

class CustomAccess
{
    public function checkAccess(string $module, string $action): bool
    {
        $user = auth()->user();
        
        // Your custom logic here
        if ($user->isAdmin()) {
            return true; // Admins can do everything
        }
        
        if ($user->isOwner($module)) {
            return in_array($action, ['index', 'edit']); // Owners can view and edit
        }
        
        return false; // Default deny
    }
    
    public function executeAccess(string $module, string $action): bool
    {
        $access = $this->checkAccess($module, $action);
        if (!$access) {
            abort(403, 'Insufficient permissions');
        }
        return $access;
    }
}
```

Then configure it:

```php
// config/crud.php
'access_class' => App\Security\CustomAccess::class,
```

### Complex Access Logic

```php
protected function setup()
{
    $this->onAccessCheck(function($method) {
        $user = auth()->user();
        
        // Time-based access
        if ($method === 'create' && now()->hour < 9) {
            return false; // No creating before 9 AM
        }
        
        // Role-based access
        if ($method === 'destroy' && !$user->hasRole('admin')) {
            return false; // Only admins can delete
        }
        
        // Resource-specific access
        if ($method === 'edit' && request()->id) {
            $resource = $this->model()->find(request()->id);
            return $user->canEdit($resource);
        }
        
        return null; // Default behavior
    });
}
```

## Examples

### 1. Public API Controller

```php
class PublicApiController extends Controller
{
    use Crud;
    
    protected $accessControlEnabled = false;
    
    protected function setScaffolder(): string
    {
        return PublicDataScaffolder::class;
    }
    
    // All methods are public - no authentication required
}
```

### 2. Mixed Access Controller

```php
class DashboardController extends Controller
{
    use Crud;
    
    protected function setScaffolder(): string
    {
        return DashboardScaffolder::class;
    }
    
    protected function setup()
    {
        $this->onAccessCheck(function($method) {
            // Public methods
            if (in_array($method, ['index', 'stats'])) {
                return false; // No auth required
            }
            
            // Admin only methods
            if (in_array($method, ['settings', 'cleanup'])) {
                return auth()->user()?->isAdmin() ?? false;
            }
            
            // Default access control for other methods
            return null;
        });
    }
}
```

### 3. Owner-Only Controller

```php
class ProfileController extends Controller
{
    use Crud;
    
    protected function setScaffolder(): string
    {
        return ProfileScaffolder::class;
    }
    
    protected function setup()
    {
        $this->onAccessCheck(function($method) {
            $user = auth()->user();
            $profileId = request()->route('id') ?? request()->input('id');
            
            // Users can only access their own profile
            if ($profileId && $profileId != $user->id) {
                return false; // Access denied
            }
            
            return null; // Default behavior
        });
    }
}
```

## Best Practices

### 1. Security by Default
- Always enable access control unless you specifically need public access
- Use `$accessControlEnabled = false` only for truly public controllers
- Prefer specific method exclusions over disabling entire controllers

### 2. Clear Intent
- Use descriptive method names that indicate their access level
- Group public methods together in your access control logic
- Document special access requirements

### 3. Consistent Patterns
- Use the same access control patterns across your application
- Create helper methods for common access patterns
- Consider creating base controllers for different access levels

### 4. Testing
- Test both positive and negative access scenarios
- Verify that UI elements are properly hidden/shown
- Test custom access logic thoroughly

## Troubleshooting

### Common Issues

**1. 403 Forbidden errors**
- Check if `accessLevelControl` is set to 'off' in config
- Verify user permissions structure
- Check `onAccessCheck` hook logic

**2. Buttons showing but requests failing**
- Ensure UI filtering and method protection use same logic
- Check for caching issues in permissions
- Verify custom access class implementation

**3. Access control not working**
- Ensure you're using the `Crud` trait
- Check if controller extends the correct base class
- Verify configuration is loaded properly

### Debug Mode

Enable detailed access control logging:

```php
// In your custom access class
public function checkAccess(string $module, string $action): bool
{
    $result = // ... your logic
    
    if (config('app.debug')) {
        logger("Access check: {$module}.{$action} = " . ($result ? 'ALLOWED' : 'DENIED'));
    }
    
    return $result;
}
```

## Migration Guide

If you're upgrading from an older version:

### From Middleware-Based Access Control

**Old way:**
```php
Route::middleware(['auth', 'acl:users.create'])->post('/users', [UserController::class, 'store']);
```

**New way:**
```php
// Automatic! Just ensure user permissions are set correctly
// No route middleware needed
```

### From Manual Access Checks

**Old way:**
```php
public function store() {
    if (!$this->user()->can('create', 'users')) {
        abort(403);
    }
    // ... method logic
}
```

**New way:**
```php
// Automatic! Access is checked before method runs
public function store() {
    // ... method logic only
}
```

The new system handles all access control automatically while providing complete flexibility for custom requirements.
