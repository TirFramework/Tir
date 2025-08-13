# Quick Reference - Access Control

## Quick Setup

### 1. Disable Access Control Completely
```php
protected $accessControlEnabled = false;
```

### 2. Control Specific Methods
```php
protected function setup()
{
    $this->onAccessCheck(function($method) {
        if (in_array($method, ['public1', 'public2'])) {
            return false; // Skip access check
        }
        return null; // Default behavior
    });
}
```

### 3. Global Configuration
```php
// config/crud.php
'accessLevelControl' => 'off', // Disable globally
```

## User Permissions Format
```php
Auth::user()->permissions = [
    'module_name' => [
        'method_name' => true/false,
    ],
];
```

## Access Control Flow
1. Check global config
2. Check controller property
3. Check onAccessCheck hook
4. Check user permissions
5. Allow/Deny with 403

## Common Patterns

### Public Controller
```php
class PublicController extends Controller
{
    use Crud;
    protected $accessControlEnabled = false;
}
```

### Mixed Access
```php
$this->onAccessCheck(function($method) {
    // Public methods
    if (in_array($method, ['index', 'show'])) {
        return false;
    }
    // Admin only
    if ($method === 'destroy') {
        return auth()->user()?->isAdmin() ?? false;
    }
    return null; // Default
});
```

### Owner Only
```php
$this->onAccessCheck(function($method) {
    $id = request()->route('id');
    return $id == auth()->id();
});
```

## Troubleshooting

- **403 errors**: Check config, permissions structure, hook logic
- **Buttons showing but failing**: Verify UI/backend consistency
- **Not working**: Ensure using Crud trait and proper base class

See [ACCESS_CONTROL.md](ACCESS_CONTROL.md) for complete documentation.
