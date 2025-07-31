# SampleModel - Comprehensive CRUD Example

This is a complete example implementation showcasing all features and field types available in the Tir CRUD framework. It demonstrates the clean separation between Model, Scaffolder, and Controller responsibilities.

## 🚀 Quick Start

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Seed Sample Data
```bash
php artisan db:seed --class=SampleModelSeeder
```

### 3. Add Routes (if not already included)
Include the routes in your `routes/web.php` or `routes/api.php`:
```php
require __DIR__ . '/sample-model.php';
```

### 4. Access the CRUD Interface
- **List Items**: `GET /sample-models`
- **Create New**: `GET /sample-models/create`
- **Edit Item**: `GET /sample-models/{id}/edit`
- **View Item**: `GET /sample-models/{id}`

## 📁 File Structure

```
app/
├── Models/
│   └── SampleModel.php          # Eloquent model with relationships
├── Http/Controllers/
│   └── SampleModelController.php # Controller with custom endpoints
└── Scaffolders/
    └── SampleModelScaffolder.php # UI definition and validation

database/
├── migrations/
│   └── 2025_07_31_000001_create_sample_models_table.php
├── factories/
│   └── SampleModelFactory.php   # Factory for testing
└── seeders/
    └── SampleModelSeeder.php     # Sample data seeder

routes/
└── sample-model.php             # Complete route definitions
```

## 🎯 Architecture Highlights

### Clean Separation of Concerns

#### 🗃️ **Model** (`SampleModel.php`)
- **Responsibility**: Data persistence, relationships, business logic
- **Features**:
  - Eloquent relationships (User, Parent/Child)
  - JSON field casting
  - Accessors & Mutators
  - Query scopes
  - Soft deletes

#### 🎨 **Scaffolder** (`SampleModelScaffolder.php`)
- **Responsibility**: UI definition, field configuration, validation rules
- **Features**:
  - All field types demonstrated
  - Conditional field display
  - Custom validation messages
  - Magic method access (`$this->property`)
  - Helper method access (`$this->hasValue()`)

#### 🎮 **Controller** (`SampleModelController.php`)
- **Responsibility**: Request orchestration, custom endpoints
- **Features**:
  - Standard CRUD operations (inherited)
  - Custom bulk operations
  - Statistics endpoint
  - Export functionality
  - Minimal code required

## 🔧 Field Types Demonstrated

### Text & Content Fields
```php
Text::make('title')                    // Single line text
TextArea::make('description')          // Multi-line text
Text::make('email')                    // Email with validation
Text::make('website_url')              // URL with validation
```

### Number Fields
```php
Number::make('priority')               // Integer input
Number::make('price')                  // Decimal/currency
Number::make('rating')                 // Rating system
```

### Boolean Fields
```php
CheckBox::make('is_active')            // Checkbox input
SwitchBox::make('is_featured')         // Toggle switch
```

### Date & Time Fields
```php
DatePicker::make('publish_date')       // Date selection
Text::make('open_time')                // Time input with validation
```

### Selection Fields
```php
Select::make('status')                 // Dropdown with options
Select::make('user_id')                // Relationship dropdown
```

### File Upload Fields
```php
FileUploader::make('avatar')           // Image upload
FileUploader::make('document_file')    // Document upload
```

### JSON/Complex Fields
```php
Additional::make('metadata')           // Nested field groups
Additional::make('settings')           // Complex configurations
```

## 💡 Advanced Features

### Conditional Field Display
```php
// Magic method approach (Laravel-like)
->showOnEditing(isset($this->name))

// Helper method approach (explicit)
->showOnEditing($this->hasValue('email'))

// Value-based conditions
->showOnIndex($this->getValue('status') !== 'deleted')
```

### Custom Validation
```php
->rules(['required', 'string', 'max:255'])
->rules(['nullable', 'email', 'unique:sample_models,email'])
```

### Relationship Handling
```php
// Automatic relationship loading
Select::make('user_id')
    ->data($this->getUserOptions())
    ->rules(['exists:users,id'])
```

### File Upload Configuration
```php
FileUploader::make('avatar')
    ->rules(['image', 'max:2048'])    // 2MB max
    ->hideFromIndex()                  // Don't show in listings
```

## 🛠️ Customization Examples

### Adding Custom Endpoints
```php
// In SampleModelController.php
public function bulkAction(Request $request)
{
    // Handle bulk operations
}

public function statistics()
{
    // Return analytics data
}
```

### Custom Route Definitions
```php
// In routes/sample-model.php
Route::post('sample-models/bulk', [SampleModelController::class, 'bulkAction']);
Route::get('sample-models/stats', [SampleModelController::class, 'statistics']);
```

### Factory States for Testing
```php
// Create featured items
SampleModel::factory()->featured()->create();

// Create premium items with files
SampleModel::factory()->premium()->withFiles()->create();

// Create parent-child relationships
SampleModel::factory()->withParent()->create();
```

## 🔐 Security Features

### Validation & Sanitization
- All inputs validated through Laravel validation rules
- File uploads restricted by type and size
- SQL injection protection through Eloquent ORM
- XSS protection through proper escaping

### Access Control
- Route middleware for authentication
- ACL integration ready
- Soft delete for data safety
- Audit trail through timestamps

### File Upload Security
```php
FileUploader::make('document')
    ->rules(['file', 'mimes:pdf,doc,docx', 'max:10240'])  // 10MB max
```

## 📊 Testing & Development

### Generate Test Data
```bash
# Create 50 random items
php artisan tinker
>>> SampleModel::factory(50)->create()

# Create specific test scenarios
>>> SampleModel::factory()->featured()->create()
>>> SampleModel::factory()->premium()->withFiles()->create()
```

### Database Queries
```php
// Using model scopes
SampleModel::published()->featured()->get()
SampleModel::byStatus('published')->byType('premium')->get()

// Relationships
$model = SampleModel::with(['user', 'children'])->first()
$model->user->name
$model->children->count()
```

## 📈 Performance Considerations

### Database Optimization
- Indexes on frequently queried fields
- Eager loading for relationships
- Pagination for large datasets
- Soft deletes instead of hard deletes

### File Handling
- Image optimization recommendations
- Storage path configuration
- CDN integration ready

## 🤝 Contributing

When extending this example:

1. **Follow the patterns**: Keep the separation of concerns
2. **Add tests**: Include factory states for new fields
3. **Document changes**: Update this README
4. **Validate security**: Ensure proper validation rules

## 📚 Related Documentation

- [Scaffolder Guide](../Scaffolders/README.md)
- [Field Types Reference](../docs/field-types.md)
- [Validation Rules](../docs/validation.md)
- [Relationship Handling](../docs/relationships.md)

This example serves as a comprehensive reference for building CRUD interfaces with the Tir framework. It demonstrates best practices, security considerations, and advanced features while maintaining clean, maintainable code.
