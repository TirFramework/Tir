# Field Accessor Guide

## Overview

The `accessor()` method allows you to transform or override field values similar to Laravel's model accessors (`getAttribute`). This gives you complete control over how field values are displayed and manipulated before being sent to the frontend.

## Signature

```php
public function accessor(callable $callback): static
```

**Parameters:**
- `$callback`: A callable that receives `($value, $model)` and returns the transformed value
  - `$value`: The original value extracted from the model
  - `$model`: The full model instance for additional context

## Use Cases

### 1. Simple Value Transformation

Transform the value before display:

```php
Text::make('title')
    ->accessor(fn($value) => strtoupper($value))
```

**Result:** "hello world" becomes "HELLO WORLD"

---

### 2. Override Value with Model Data

Use different model data instead of the field's default value:

```php
Text::make('display_name')
    ->accessor(fn($value, $model) => $model->first_name . ' ' . $model->last_name)
```

**Result:** Shows full name instead of display_name field

---

### 3. Format Dates

Apply custom date formatting:

```php
Text::make('created_at')
    ->accessor(fn($value) => $value ? \Carbon\Carbon::parse($value)->format('F j, Y') : 'N/A')
```

**Result:** "2025-10-21" becomes "October 21, 2025"

---

### 4. Conditional Formatting

Apply different transformations based on conditions:

```php
Text::make('price')
    ->accessor(function($value, $model) {
        if ($model->is_premium) {
            return '$' . number_format($value * 0.9, 2) . ' (10% off)';
        }
        return '$' . number_format($value, 2);
    })
```

**Result:** Shows discounted price for premium users

---

### 5. JSON Formatting

Format JSON or array data:

```php
Text::make('settings')
    ->accessor(function($value) {
        if (is_array($value)) {
            return json_encode($value, JSON_PRETTY_PRINT);
        }
        return $value;
    })
```

**Result:** Prettified JSON output

---

### 6. Relation Data Manipulation

Transform relational data:

```php
Select::make('categories')
    ->relation('categories', 'name')
    ->accessor(function($value, $model) {
        // $value is array of category IDs or names
        if (is_array($value)) {
            return array_map('strtoupper', $value);
        }
        return $value;
    })
```

**Result:** Category names in uppercase

---

### 7. Calculate Derived Values

Create computed values based on multiple fields:

```php
Text::make('total_revenue')
    ->virtual()
    ->accessor(fn($value, $model) => $model->quantity * $model->unit_price)
```

**Result:** Calculates total from quantity × unit_price

---

### 8. HTML/Badge Formatting

Add HTML formatting (for display fields):

```php
Text::make('status')
    ->accessor(function($value) {
        return match($value) {
            'active' => '<span class="badge badge-success">Active</span>',
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'inactive' => '<span class="badge badge-danger">Inactive</span>',
            default => $value
        };
    })
```

**Result:** Styled status badges

---

### 9. Truncate Long Text

Limit text length for index pages:

```php
Text::make('description')
    ->accessor(function($value) {
        return strlen($value) > 100 
            ? substr($value, 0, 100) . '...' 
            : $value;
    })
```

**Result:** Truncated descriptions with ellipsis

---

### 10. Boolean to Text Conversion

Convert boolean values to readable text:

```php
Text::make('is_active')
    ->accessor(fn($value) => $value ? 'Yes' : 'No')
```

**Result:** true → "Yes", false → "No"

---

### 11. Array to Comma-Separated String

Convert arrays to readable strings:

```php
Text::make('tags')
    ->accessor(function($value) {
        if (is_array($value)) {
            return implode(', ', $value);
        }
        return $value;
    })
```

**Result:** `['php', 'laravel', 'vue']` becomes "php, laravel, vue"

---

### 12. Access Related Model Data

Get data from related models:

```php
Text::make('author_email')
    ->virtual()
    ->accessor(fn($value, $model) => $model->author?->email ?? 'No author')
```

**Result:** Shows author's email from relationship

---

## Complete Example in Scaffolder

```php
protected function setFields(): array
{
    return [
        // Example 1: Transform title to uppercase
        Text::make('title')
            ->accessor(fn($value) => strtoupper($value)),

        // Example 2: Format price with currency
        Text::make('price')
            ->accessor(fn($value) => '$' . number_format($value, 2)),

        // Example 3: Show full name from first + last
        Text::make('full_name')
            ->virtual()
            ->accessor(fn($value, $model) => 
                $model->first_name . ' ' . $model->last_name
            ),

        // Example 4: Format date nicely
        Text::make('published_at')
            ->accessor(fn($value) => 
                $value ? \Carbon\Carbon::parse($value)->diffForHumans() : 'Not published'
            ),

        // Example 5: Badge for status
        Text::make('status')
            ->accessor(function($value) {
                $badges = [
                    'draft' => '<span class="badge badge-secondary">Draft</span>',
                    'published' => '<span class="badge badge-success">Published</span>',
                    'archived' => '<span class="badge badge-warning">Archived</span>',
                ];
                return $badges[$value] ?? $value;
            }),

        // Example 6: Transform relation values
        Select::make('categories')
            ->relation('categories', 'name')
            ->multiple()
            ->accessor(function($value, $model) {
                // Add count to each category name
                if (is_array($value)) {
                    return array_map(fn($cat) => $cat . ' (' . $model->categories->count() . ')', $value);
                }
                return $value;
            }),

        // Example 7: Calculate from multiple fields
        Text::make('total')
            ->virtual()
            ->accessor(fn($value, $model) => 
                number_format($model->quantity * $model->unit_price, 2)
            ),

        // Example 8: Conditional display
        Text::make('discount')
            ->accessor(function($value, $model) {
                if ($model->is_premium) {
                    return $value . '% + Premium Bonus';
                }
                return $value . '%';
            }),
    ];
}
```

## Important Notes

### Execution Order

1. **Extract raw value** from model (field or relation)
2. **Apply accessor** if defined
3. **Set final value** to `$this->value`

### Null Safety

Always handle null values in your accessor:

```php
->accessor(fn($value) => $value ? strtoupper($value) : 'N/A')
```

### Virtual Fields

For computed/derived fields, mark them as virtual:

```php
Text::make('computed_field')
    ->virtual()  // Not a real database column
    ->accessor(fn($value, $model) => $model->field1 + $model->field2)
```

### Performance Consideration

Accessors run every time the field is retrieved. Avoid heavy operations:

```php
// ❌ BAD - Heavy database query in accessor
->accessor(fn($value, $model) => 
    $model->orders()->sum('total')  // N+1 query problem!
)

// ✅ GOOD - Use eager loading or cache
->accessor(fn($value, $model) => 
    $model->orders_sum_total  // Already loaded via withSum()
)
```

## Comparison with Laravel Model Accessors

| Feature | Laravel Model Accessor | Field Accessor |
|---------|----------------------|----------------|
| **Scope** | Model-wide | Field-specific |
| **Definition** | In model class | In scaffolder |
| **Access to model** | `$this` | `$model` parameter |
| **Use case** | General attribute transformation | UI-specific formatting |
| **Reusability** | Reused across app | Specific to scaffolder |

## Best Practices

1. **Keep it simple** - Complex logic should be in the model
2. **Use for UI formatting** - Perfect for display transformations
3. **Document complex accessors** - Add comments for business logic
4. **Test edge cases** - Handle null, empty, and unexpected values
5. **Consider performance** - Avoid N+1 queries and heavy computations

## Advanced Pattern: Reusable Accessors

Create reusable accessor functions:

```php
class FieldAccessors
{
    public static function currency(string $symbol = '$', int $decimals = 2): callable
    {
        return fn($value) => $symbol . number_format($value, $decimals);
    }

    public static function uppercase(): callable
    {
        return fn($value) => strtoupper($value);
    }

    public static function truncate(int $length = 100): callable
    {
        return fn($value) => strlen($value) > $length 
            ? substr($value, 0, $length) . '...' 
            : $value;
    }
}

// Usage in scaffolder:
Text::make('price')
    ->accessor(FieldAccessors::currency('€', 2))

Text::make('title')
    ->accessor(FieldAccessors::uppercase())

Text::make('description')
    ->accessor(FieldAccessors::truncate(150))
```

## Conclusion

The `accessor()` method provides a powerful, Laravel-like way to transform field values. Use it for UI-specific formatting while keeping your models clean and focused on business logic.
