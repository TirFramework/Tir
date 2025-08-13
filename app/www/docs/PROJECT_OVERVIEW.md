# Tir Framework - CRUD System with Hook Architecture

## Project Overview

The Tir Framework is a PHP Laravel-based CRUD (Create, Read, Update, Delete) system that implements a sophisticated hook architecture pattern. This project focuses on providing a clean, extensible, and maintainable approach to building CRUD operations with customizable behaviors through hooks.

## Architecture Goals

### Primary Objectives
1. **Clean Architecture**: Implement a trait-based CRUD system that separates concerns and promotes code reusability
2. **Hook Pattern**: Transform from traditional before/after hooks to "on" hooks with default implementations as closures
3. **Extensibility**: Allow developers to easily customize CRUD operations without modifying core framework code
4. **Method Isolation**: Resolve method name conflicts between traits by making core methods private

## Project Structure

### Core Components

#### 1. Controllers
- **CrudController** (`/vendor/tir/crud/src/Controllers/CrudController.php`)
  - Abstract base controller that combines all CRUD traits
  - Uses: `CrudInit, Index, Data, Show, Create, Edit, Destroy`

- **Crud** (`/vendor/tir/crud/src/Controllers/Crud.php`) **[NEW APPROACH]**
  - Trait-based alternative to CrudController
  - Can be used in any controller: `use Crud;`
  - Provides same functionality as CrudController but as a trait
  - Both CrudController and Crud trait approaches work

- **CrudInit** (`/vendor/tir/crud/src/Controllers/CrudInit.php`)
  - Handles initialization and setup
  - Auto-calls `setup()` method if it exists in child controllers

#### 2. CRUD Traits
- **Create** (`/vendor/tir/crud/src/Controllers/Create.php`)
  - Handles create/store operations
  - Uses: `ProcessRequest, StoreHooks, RequestHooks`
  - Key methods: `create()`, `store()`, `storeCrud()`, `storeModel()`

- **Edit** (`/vendor/tir/crud/src/Controllers/Edit.php`)
  - Handles edit/update operations
  - Uses: `ProcessRequest, UpdateHooks, RequestHooks`
  - Key methods: `edit()`, `update()`, `updateCrud()`, `updateModel()`

- **Index** (`/vendor/tir/crud/src/Controllers/Index.php`)
  - Handles listing and data retrieval
  - Uses: `Data, IndexHooks`

#### 3. Hook System

##### Hook Traits
- **BaseHooks** (`/vendor/tir/crud/src/Support/Hooks/BaseHooks.php`)
  - Foundation trait for all hook implementations
  - Provides `callHook()` method and `$crudHookCallbacks` storage

- **StoreHooks** (`/vendor/tir/crud/src/Support/Hooks/StoreHooks.php`)
  - Hooks for create/store operations
  - Methods: `onStore()`, `onSaveModel()`, `onFillModelForStore()`, `onStoreRelations()`, `onStoreCompleted()`

- **UpdateHooks** (`/vendor/tir/crud/src/Support/Hooks/UpdateHooks.php`)
  - Hooks for edit/update operations
  - Methods: `onUpdate()`, `onFillModelForUpdate()`, `onUpdateModel()`, `onUpdateRelations()`, `onUpdateCompleted()`

- **IndexHooks** (`/vendor/tir/crud/src/Support/Hooks/IndexHooks.php`)
  - Hooks for listing/index operations
  - Methods: `onSelect()`, `onFilter()`, `onSort()`, `onIndexResponse()`

- **RequestHooks** (`/vendor/tir/crud/src/Support/Hooks/RequestHooks.php`)
  - Hooks for request processing
  - Methods: `onProcessRequest()`

#### 4. Response Layer
- **CrudResponse** (`/vendor/tir/crud/src/Support/Response/CrudResponse.php`)
  - Handles CRUD-specific response formatting and translations
  - Methods: `store()`, `update()` 
  - Integrates with scaffolder for module-specific messages
  - **Maintains separation of concerns** - keeps CRUD response logic separate from general HTTP responses

## Hook Pattern Implementation

### Before/After vs "On" Hooks

#### Traditional Pattern (Old)
```php
// Before/After pattern
$this->beforeStore($model, $request);
$model->save();
$this->afterStore($model, $request);
```

#### "On" Hook Pattern (New)
```php
// Define default behavior as closure
$defaultSaveModel = function($m = null, $r = null) use ($model, $request) {
    if ($m !== null) $model = $m;
    if ($r !== null) $request = $r;
    $model->save();
    return $model;
};

// Pass closure to hook - can be overridden
$customSaveModel = $this->callHook('onSaveModel', $defaultSaveModel, $model, $request);
if($customSaveModel !== null) {
    return $customSaveModel;
}

// Otherwise, use default behavior
return $defaultSaveModel();
```

### Hook Usage Example

```php
// Approach 1: Using CrudController (inheritance)
class MinimalExampleController extends CrudController
{
    protected function setup()
    {
        // Store hooks
        $this->onFillModelForStore(function ($defaultFill, $model, $request) {
            $data = $request->all();
            $data['title'] = $data['title'] . ' - Modified by hook';
            return $model->fill($data);
        });

        $this->onSaveModel(function ($defaultSave, $model, $request) {
            \Log::info('Saving model: ' . $model->title);
            $model->save();
            return $model;
        });
    }
}

// Approach 2: Using Crud trait (composition) - NEW
class MinimalExampleController extends Controller
{
    use Crud;

    protected function setup()
    {
        // Update hooks
        $this->onFillModelForUpdate(function ($defaultFill, $model, $request) {
            $data = $request->all();
            $data['description'] = $data['description'] . ' - Updated on ' . date('Y-m-d');
            return $model->fill($data);
        });
    }
}
```

## Key Technical Decisions

### 1. Method Visibility
- Made `fill()` and `save()` methods private in Create.php and Edit.php traits
- Prevents method name conflicts when multiple traits are used in the same class
- Ensures encapsulation of core functionality

### 2. Hook Method Naming
- Standardized on descriptive hook names: `onFillModelForStore`, `onSaveModel`, `onStoreRelations`
- Each hook receives the default behavior as the first parameter (closure)
- Additional parameters passed as needed for context

### 3. Return Type Consistency
- Hook methods return `self` for method chaining
- Core CRUD methods maintain Laravel's JsonResponse return types
- Hook callbacks can return modified data or null to use defaults

### 4. Response Layer Separation
- **CrudResponse**: Handles CRUD-specific formatting, translations, and data structures
- **Laravel Response**: Handles general HTTP response concerns
- Maintains clean separation between framework responses and business logic
- Allows for response customization through potential ResponseHooks

## Development Workflow

### Adding New Hooks
1. Define hook method in appropriate Hook trait (StoreHooks, UpdateHooks, etc.)
2. Add hook call in corresponding controller trait (Create.php, Edit.php, etc.)
3. Implement hook usage in controller's `setup()` method

### Example Hook Implementation
```php
// In StoreHooks.php
protected function onCustomAction(callable $callback): self
{
    $this->crudHookCallbacks['onCustomAction'] = $callback;
    return $this;
}

// In Create.php
$defaultCustomAction = function($data) {
    // Default behavior
    return $data;
};

$customAction = $this->callHook('onCustomAction', $defaultCustomAction, $data);
if($customAction !== null) {
    return $customAction;
}
return $defaultCustomAction();
```

## Docker Environment

The project runs in a Docker environment with:
- PHP application container: `tir-app`
- MySQL database container
- Nginx web server

### Common Commands
```bash
# Regenerate autoload files
docker exec tir-app composer dump-autoload

# Access application container
docker exec -it tir-app bash
```

## Current State & Progress

### Completed Features
- ✅ Refactored Create.php to use "on" hooks pattern
- ✅ Refactored Edit.php to use "on" hooks pattern  
- ✅ Made fill/save methods private to avoid conflicts
- ✅ Fixed BaseHooks namespace imports
- ✅ Created comprehensive hook traits (StoreHooks, UpdateHooks, IndexHooks)
- ✅ Implemented MinimalExampleController with hook examples
- ✅ **Added Crud.php trait as alternative to CrudController inheritance**
- ✅ **Both inheritance (CrudController) and composition (Crud trait) approaches working**

### Known Issues
- Hook method compatibility between traits and CrudController return types
- Some hook methods may need adjustment for proper inheritance

### Next Steps
1. Test hook functionality in real CRUD operations
2. Add more comprehensive hook examples
3. Document advanced hook patterns
4. Create scaffolding generators that include hook setup

## For AI Assistants & Copilot

### Context Understanding
- This is a **trait-based architecture** - multiple traits are composed to create functionality
- **Two approaches available**: Inheritance (extend CrudController) or Composition (use Crud trait)
- **Hooks are the primary extension mechanism** - don't modify core traits, use hooks instead
- **Private methods prevent conflicts** - when traits have similar method names
- **Setup method is auto-called** - controllers can implement setup() to configure hooks
- **Flexible implementation** - Choose inheritance or trait composition based on your needs

### Common Tasks
- **Adding hooks**: Always add to appropriate Hook trait first, then call in controller trait
- **Debugging**: Check hook method names match between definition and usage
- **Method conflicts**: Make methods private when traits might conflict
- **Testing**: Use MinimalExampleController as a testing ground for new hooks

### File Locations Reference
```
/vendor/tir/crud/src/
├── Controllers/
│   ├── CrudController.php (inheritance approach)
│   ├── Crud.php (trait approach - NEW)
│   ├── Create.php (store operations)
│   ├── Edit.php (update operations)
│   └── Index.php (listing operations)
└── Support/Hooks/
    ├── BaseHooks.php (foundation)
    ├── StoreHooks.php (create/store hooks)
    ├── UpdateHooks.php (edit/update hooks)
    └── IndexHooks.php (listing hooks)
```

## Implementation Approaches

### Approach 1: Inheritance (Traditional)
```php
class MyController extends CrudController
{
    protected function setScaffolder(): string
    {
        return MyScaffolder::class;
    }
}
```

### Approach 2: Trait Composition (New & Flexible)
```php
class MyController extends Controller
{
    use Crud;
    
    protected function setScaffolder(): string
    {
        return MyScaffolder::class;
    }
}
```

Both approaches provide the same functionality and hook capabilities.

This architecture promotes clean, maintainable code while providing powerful customization capabilities through the hook system.
