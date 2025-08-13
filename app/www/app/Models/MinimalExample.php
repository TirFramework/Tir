<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

/**
 * MinimalExample - Demonstrating Auto-Fillable Generation with Many-to-Many Relationship
 *
 * This model shows how the Tir framework automatically handles
 * fillable attributes when they're not manually defined, and includes
 * a many-to-many relationship with users.
 */
class MinimalExample extends Model
{
    use HasFactory;

    /**
     * Auto-Fillable Example
     *
     * When $fillable is empty or not defined, the framework will:
     * 1. Look at the associated scaffolder fields
     * 2. Include all fields marked as fillable(true) - which is the default
     * 3. Exclude fields marked as fillable(false)
     * 4. Exclude virtual fields automatically
     *
     * This means you only need to define fields once in the scaffolder!
     */
    protected $fillable = []; // Framework will auto-generate this!

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The users that belong to the minimal example.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

}
