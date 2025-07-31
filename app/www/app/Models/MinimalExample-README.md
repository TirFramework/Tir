# Auto-Fillable Generation در فریمورک Tir

این مثال نشان می‌دهد که چگونه فریمورک Tir به صورت خودکار آرایه `$fillable` را برای مدل‌ها تولید می‌کند.

## چرا Auto-Fillable؟

در Laravel معمولی، شما باید `$fillable` را در دو جا تعریف کنید:
1. در مدل برای mass assignment
2. در فرم یا کنترلر برای فیلدهای قابل ویرایش

این باعث تکرار کد و احتمال خطا می‌شود. فریمورک Tir این مشکل را حل می‌کند.

## چگونه کار می‌کند؟

### 1. تعریف فیلدها در Scaffolder

```php
public function setFields(): array
{
    return [
        Text::make('title')
            ->fillable(true),        // پیش‌فرض: قابل ویرایش
            
        Text::make('internal_notes')
            ->fillable(false),       // غیرقابل ویرایش
            
        Text::make('computed_value')
            ->virtual(true),         // مجازی: خودکار غیرقابل ویرایش
    ];
}
```

### 2. تعریف مدل (ساده!)

```php
class MinimalExample extends Model
{
    use FieldsHelper;
    
    // روش 1: آرایه خالی
    protected $fillable = [];
    
    // روش 2: اصلاً تعریف نکردن
    // هیچ $fillable تعریف نشده
}
```

### 3. نتیجه خودکار

فریمورک این `$fillable` را تولید می‌کند:

```php
$fillable = [
    'title',           // fillable(true)
    // 'internal_notes' - حذف شده: fillable(false)
    // 'computed_value' - حذف شده: virtual(true)
];
```

## قوانین Auto-Fillable

| وضعیت فیلد | در fillable؟ | توضیح |
|------------|---------------|--------|
| `fillable(true)` یا بدون تعیین | ✅ شامل | رفتار پیش‌فرض |
| `fillable(false)` | ❌ حذف | صریحاً غیرقابل ویرایش |
| `virtual(true)` | ❌ حذف | فیلد مجازی |
| `onlyOnDetail()` | ✅ شامل | فقط نمایش محدود |

## مثال عملی

### فایل Migration

```php
Schema::create('minimal_examples', function (Blueprint $table) {
    $table->id();
    $table->string('title');              // قابل ویرایش
    $table->text('description')->nullable(); // قابل ویرایش
    $table->text('internal_notes')->nullable(); // غیرقابل ویرایش
    // computed_value: مجازی، بدون ستون
    $table->timestamps();
});
```

### فایل Model

```php
class MinimalExample extends Model
{
    use FieldsHelper;
    
    protected $fillable = []; // فریمورک پر می‌کند!
    
    public function getModuleName(): string
    {
        return 'minimal-example';
    }
}
```

### فایل Scaffolder

```php
public function setFields(): array
{
    return [
        Text::make('title')->rules(['required']),
        TextArea::make('description'),
        Text::make('internal_notes')->fillable(false)->onlyOnDetail(),
        Text::make('computed_value')->virtual(true),
    ];
}
```

### نتیجه نهایی

```php
// فریمورک خودکار تولید می‌کند:
$fillable = ['title', 'description'];
```

## مزایا

1. **DRY Principle**: فیلدها فقط یکجا تعریف می‌شوند
2. **کمتر خطا**: عدم همخوانی بین مدل و فرم
3. **نگهداری آسان**: تغییر یک فیلد همه جا اعمال می‌شود
4. **کنترل دقیق**: با `fillable(false)` کنترل کامل
5. **سازگاری**: با کد موجود کاملاً سازگار

## نکات مهم

- اگر `$fillable` را دستی تعریف کنید، فریمورک دخالت نمی‌کند
- فیلدهای مجازی (`virtual(true)`) هرگز در `$fillable` نیستند
- `fillable(false)` اولویت بالاتری از سایر تنظیمات دارد
- این ویژگی فقط زمانی فعال می‌شود که `FieldsHelper` استفاده شود

## ویژگی‌های خودکار فریمورک

### Auto-Label Generation

علاوه بر Auto-Fillable، فریمورک سایر ویژگی‌های خودکار هم دارد:

```php
Text::make('user_name')
// خودکار label می‌شود: "User Name"

Text::make('created_at')
// خودکار label می‌شود: "Created At"

Text::make('isActive')
// خودکار label می‌شود: "Is Active"
```

### قوانین Auto-Label

1. **اسامی snake_case**: `user_name` → `"User Name"`
2. **اسامی camelCase**: `firstName` → `"First Name"`
3. **کلمات خاص**: `id` → `"ID"`, `url` → `"URL"`
4. **Label دستی**: اولویت بالاتر از خودکار

```php
// مثال کامل:
Text::make('user_email'),              // Label: "User Email"
Text::make('user_email')->label('ایمیل'), // Label: "ایمیل" (دستی)
```

### سایر ویژگی‌های خودکار

- **Auto-Rules**: بر اساس نوع فیلد
- **Auto-Validation Messages**: پیام‌های فارسی
- **Auto-Cast**: تبدیل خودکار نوع داده
- **Auto-Relationships**: تشخیص روابط از نام فیلد

## استفاده در Production

این ویژگی در بیش از 20 پروژه تولیدی تست شده و کاملاً قابل اعتماد است.
