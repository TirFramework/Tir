# IndexHooks Architecture Flow

## Overview
The IndexHooks system works through a **dual-trait architecture** where the same trait (`IndexHooks`) is used in both the controller and service layers, but for different purposes.

## Step-by-Step Flow

### 1. Hook Registration (Controller Side)
```php
// MinimalExampleController.php
class MinimalExampleController extends CrudController
{
    protected function setup()
    {
        // This calls IndexHooks::onSelect() in the CONTROLLER
        $this->onSelect(function ($defaultSelect, $query) {
            // User's custom logic
            return $query->where('status', 'active');
        });
    }
}
```

**What happens:**
- Controller uses `IndexHooks` trait
- `$this->onSelect()` stores the callback in controller's `$crudHookCallbacks` array
- Controller's `$crudHookCallbacks['onSelect'] = [user's function]`

### 2. Service Instantiation (Bridge)
```php
// Data.php trait
public final function data(): mixed
{
    // 1. Create service
    $CrudService = new DataService($this->scaffolder(), $this->model());
    
    // 2. BRIDGE: Pass controller's hooks to service
    if (isset($this->crudHookCallbacks)) {
        $CrudService->setHooks($this->crudHookCallbacks);
    }
    
    // 3. Service now has controller's hooks
    $items = $CrudService->getData();
    return $this->indexResponse($items);
}
```

**What happens:**
- Controller's hooks are copied to service
- Service's `$crudHookCallbacks` now contains user's functions

### 3. Hook Execution (Service Side)
```php
// DataService.php
private function select($query): mixed
{
    // Define default behavior
    $defaultSelect = function($q = null) use ($query) {
        if ($q !== null) $query = $q;
        $columns = $this->selectColumns();
        return $query->select($columns);
    };

    // Call hook with default behavior
    $customSelect = $this->callHook('onSelect', $defaultSelect, $query);
    
    if($customSelect !== null) {
        return $customSelect; // User's custom logic executed
    }
    
    return $defaultSelect(); // Default behavior
}
```

**What happens:**
- Service calls `$this->callHook('onSelect', ...)`
- Service's `IndexHooks` trait finds the hook in `$crudHookCallbacks`
- User's function executes with default behavior as first parameter

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    CONTROLLER LAYER                             │
├─────────────────────────────────────────────────────────────────┤
│  MinimalExampleController                                       │
│  ├── uses IndexHooks trait                                      │
│  ├── setup() calls $this->onSelect(function...)                │
│  └── Stores hooks in $crudHookCallbacks[]                      │
└─────────────────────────────────────────────────────────────────┘
                               │
                               │ Data.php passes hooks
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                     SERVICE LAYER                               │
├─────────────────────────────────────────────────────────────────┤
│  DataService                                                    │
│  ├── uses IndexHooks trait (same trait!)                       │
│  ├── setHooks() receives controller's hooks                    │
│  ├── Executes business logic                                   │
│  └── Calls hooks at appropriate moments                        │
└─────────────────────────────────────────────────────────────────┘
```

## Why This Works

### Same Trait, Different Roles:

1. **IndexHooks in Controller:**
   - **Purpose**: Provide hook registration methods (`onSelect`, `onFilter`, etc.)
   - **Storage**: Controller's `$crudHookCallbacks` array
   - **When**: During `setup()` method execution

2. **IndexHooks in Service:**
   - **Purpose**: Provide hook execution method (`callHook`)
   - **Storage**: Service's `$crudHookCallbacks` array (copied from controller)
   - **When**: During business logic execution

### BaseHooks Trait Magic:
```php
// Both controller and service inherit this
trait BaseHooks
{
    protected array $crudHookCallbacks = []; // Storage
    
    protected function callHook(string $hookName, ...$args) {
        if (isset($this->crudHookCallbacks[$hookName])) {
            return call_user_func_array($this->crudHookCallbacks[$hookName], $args);
        }
        return null;
    }
}
```

## Complete Example Flow

```php
// 1. User registers hook in controller
$this->onSelect(function ($defaultSelect, $query) {
    $defaultSelect(); // Call default behavior
    return $query->where('featured', true);
});

// 2. Controller stores: $crudHookCallbacks['onSelect'] = [user's function]

// 3. Data.php copies hooks to service: 
$service->setHooks($controller->crudHookCallbacks);

// 4. Service executes hook:
$result = $this->callHook('onSelect', $defaultSelect, $query);
// This calls: user's_function($defaultSelect, $query)

// 5. User's function executes:
function ($defaultSelect, $query) {
    $defaultSelect(); // Runs original select logic
    return $query->where('featured', true); // Adds custom logic
}
```

## Key Benefits

1. **Separation of Concerns**: Controller handles registration, service handles execution
2. **Code Reuse**: Same `IndexHooks` trait serves both purposes
3. **Clean API**: Users still use simple `$this->onSelect()` syntax
4. **Flexible Architecture**: Service can be tested independently
5. **No Breaking Changes**: Existing controller code works unchanged

The genius is that the **same trait serves dual purposes** through the bridge pattern implemented in `Data.php`!
