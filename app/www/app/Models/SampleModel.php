<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tir\Crud\Support\Scaffold\FieldsHelper;

/**
 * SampleModel - Comprehensive example demonstrating all field types
 *
 * This model showcases every available field type in the Tir CRUD framework,
 * including text, numbers, dates, files, relationships, and JSON fields.
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $content
 * @property string|null $email
 * @property string|null $website_url
 * @property int $priority
 * @property float|null $price
 * @property float $rating
 * @property bool $is_active
 * @property bool $is_featured
 * @property bool $is_published
 * @property string|null $publish_date
 * @property string|null $start_datetime
 * @property string|null $end_datetime
 * @property string|null $open_time
 * @property string|null $close_time
 * @property string $status
 * @property string $type
 * @property string|null $category
 * @property array|null $tags
 * @property string|null $avatar
 * @property string|null $cover_image
 * @property array|null $gallery
 * @property string|null $document_file
 * @property array|null $metadata
 * @property array|null $settings
 * @property array|null $social_links
 * @property int|null $user_id
 * @property int|null $parent_id
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class SampleModel extends Model
{
    use HasFactory, SoftDeletes, FieldsHelper;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sample_models';

    /**
     * The attributes that are mass assignable.
     *
     * IMPORTANT: Auto-Fillable Handling
     * ================================
     *
     * If this array is empty or not defined, the Tir CRUD framework will
     * automatically generate the fillable fields based on the scaffolder
     * field definitions. This provides several benefits:
     *
     * 1. DRY Principle: No need to duplicate field names
     * 2. Automatic Sync: Adding/removing scaffolder fields automatically
     *    updates fillable attributes
     * 3. Security: Only fields defined in scaffolder can be mass assigned
     * 4. Consistency: Ensures scaffolder and model stay in sync
     *
     * How it works:
     * - The framework scans all fields in the associated scaffolder
     * - Fields marked as fillable(true) are included (default behavior)
     * - Fields marked as fillable(false) are excluded
     * - Virtual fields are automatically excluded
     * - File upload fields are included for path storage
     *
     * Manual Override:
     * If you need custom control, you can manually define this array.
     * When manually defined, the auto-generation is bypassed.
     *
     * Examples:
     *
     * // Auto-generation (recommended):
     * protected $fillable = [];
     *
     * // Manual definition (when needed):
     * protected $fillable = ['title', 'description', 'status'];
     *
     * // Exclude specific fields in scaffolder:
     * Text::make('internal_notes')->fillable(false)
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'email',
        'website_url',
        'priority',
        'price',
        'rating',
        'is_active',
        'is_featured',
        'is_published',
        'publish_date',
        'start_datetime',
        'end_datetime',
        'open_time',
        'close_time',
        'status',
        'type',
        'category',
        'tags',
        'avatar',
        'cover_image',
        'gallery',
        'document_file',
        'metadata',
        'settings',
        'social_links',
        'user_id',
        'parent_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'publish_date' => 'date',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i',
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'tags' => 'array',
        'gallery' => 'array',
        'metadata' => 'array',
        'settings' => 'array',
        'social_links' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the module name for CRUD operations
     *
     * @return string
     */
    public function getModuleName(): string
    {
        return 'sample-model';
    }

    // Relationships

    /**
     * Get the user that owns the sample model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent sample model.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(SampleModel::class, 'parent_id');
    }

    /**
     * Get the child sample models.
     */
    public function children(): HasMany
    {
        return $this->hasMany(SampleModel::class, 'parent_id');
    }

    /**
     * Get the authors (many-to-many relationship with users).
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'sample_model_user', 'sample_model_id', 'user_id');
    }

    // Accessors & Mutators

    /**
     * Get the full URL for the avatar.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    /**
     * Get the full URL for the cover image.
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    /**
     * Get the status with proper formatting.
     */
    public function getFormattedStatusAttribute(): string
    {
        return ucfirst($this->status);
    }

    /**
     * Set the slug attribute.
     */
    public function setSlugAttribute($value): void
    {
        $this->attributes['slug'] = $value ? \Str::slug($value) : \Str::slug($this->title);
    }

    // Scopes

    /**
     * Scope a query to only include active models.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include published models.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('status', 'published');
    }

    /**
     * Scope a query to only include featured models.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
