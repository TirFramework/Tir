# Access Control Layer Separation

## Problem Solved

The original implementation mixed **business logic actions** with **access control** in the same place, causing:

1. **Tight Coupling**: Scaffolders knew about ACL directly
2. **Hard to Test**: Couldn't test business logic without access control
3. **Mixed Responsibilities**: One method handled two different concerns

## New Architecture

### 1. **ActionResolver** - Combines Business + Access Control

```php
// Handles the combination of:
// - What actions the model supports (business logic)
// - What actions the user can perform (access control)

$resolver = ActionResolver::fromScaffolder($scaffolder);
$finalActions = $resolver->getResolvedActions(); // Business + ACL
$businessOnly = $resolver->getBusinessActions(); // Just business logic
```

### 2. **AccessControlService** - Handles Route-Level Access

```php
// Handles middleware application and programmatic access checks
$accessControl = AccessControlService::fromScaffolder($scaffolder);

// Apply middleware automatically
$middleware = $accessControl->getMiddleware(); // "acl:module-name"

// Programmatic checks
if ($accessControl->canPerform('edit')) {
    // Allow editing
}
```

### 3. **Separated Scaffolder Concerns**

```php
class UserScaffolder extends BaseScaffolder
{
    /**
     * BUSINESS LOGIC ONLY - What actions this model supports
     */
    protected function setActions(): array
    {
        return [
            'index' => true,
            'create' => true,
            'edit' => true,
            'show' => true,
            'destroy' => false,      // Business rule: Users can't be soft deleted
            'fullDestroy' => false,  // Business rule: No permanent deletion
        ];
    }

    /**
     * ACCESS CONTROL TOGGLE - Enable/disable ACL for this module
     */
    protected function setAcl(): bool
    {
        return true; // Enable access control checking
    }
}
```

## Usage Examples

### 1. **Using Custom Access Checker**

In your `.env`:
```env
CRUD_ACL_CHECKER_CLASS=App\Support\Acl\CustomAccessChecker
```

Or in config:
```php
// config/crud.php
'aclCheckerClass' => App\Support\Acl\CustomAccessChecker::class,
```

### 2. **Controller Usage**

```php
class UserController extends CrudController
{
    protected function setup()
    {
        // Access control is handled automatically via middleware
        
        // Programmatic access checks if needed
        if (!$this->canPerform('edit')) {
            abort(403, 'Cannot edit users');
        }
        
        // Business logic hooks
        $this->onStore(function($defaultStore, $request) {
            // Your business logic here
            return $defaultStore();
        });
    }
}
```

### 3. **Testing Benefits**

```php
// Test business logic without access control
public function test_business_actions()
{
    $scaffolder = new UserScaffolder();
    $businessActions = $scaffolder->getBusinessActions();
    
    $this->assertTrue($businessActions['index']);
    $this->assertFalse($businessActions['destroy']); // Business rule
}

// Test access control separately
public function test_access_control()
{
    $accessControl = new AccessControlService('users', true);
    
    // Mock user with specific permissions
    $this->actingAs($userWithoutDeletePermission);
    $this->assertFalse($accessControl->canPerform('destroy'));
}
```

## Benefits

1. **Separation of Concerns**: Business logic ≠ Access control
2. **Testability**: Can test each layer independently
3. **Flexibility**: Can swap access control implementations
4. **Clarity**: Clear distinction between what the system can do vs what user can do
5. **Maintainability**: Changes to access rules don't affect business logic

## Migration Path

### Before (Mixed):
```php
private function initActions(): void
{
    $baseActions = ['index' => true, 'create' => true];
    $this->actions = array_merge($baseActions, $this->actions);
    
    // ACL mixed with business logic
    if($this->getAccessLevelStatus()) {
        $checker = config('crud.aclCheckerClass');
        if ($this->actions['index']) {
            $this->actions['index'] = ($checker::check($this->moduleName, 'index') !== 'deny');
        }
        // ... more ACL checks
    }
}
```

### After (Separated):
```php
private function initActions(): void
{
    // ActionResolver handles the separation
    $this->actionResolver = ActionResolver::fromScaffolder($this);
    $this->actions = $this->actionResolver->getResolvedActions();
}
```

The new system automatically handles:
- ✅ Business action definition
- ✅ Access control application  
- ✅ Middleware setup
- ✅ Result combination
- ✅ Clean separation of concerns
