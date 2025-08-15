<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Category Model - For testing relationships
 *
 * This model demonstrates a simple category system that can be
 * related to other models like MinimalExample.
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all minimal examples that belong to this category
     */
    public function minimalExamples(): BelongsToMany
    {
        return $this->belongsToMany(MinimalExample::class);
    }
}
