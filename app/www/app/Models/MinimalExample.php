<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tir\Crud\Support\Scaffold\FieldsHelper;

/**
 * MinimalExample - Demonstrating Auto-Fillable Generation
 *
 * This model shows how the Tir framework automatically handles
 * fillable attributes when they're not manually defined.
 */
class MinimalExample extends Model
{
    use HasFactory, FieldsHelper;

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

    /**
     * Alternative: Don't define $fillable at all
     *
     * If you completely omit the $fillable property, the framework
     * will detect this and generate it automatically.
     */

    /**
     * Get the module name for CRUD operations
     */
    public function getModuleName(): string
    {
        return 'minimal-example';
    }
}

/**
 * Example Scaffolder demonstrating fillable control:
 *
 * class MinimalExampleScaffolder extends BaseScaffolder
 * {
 *     public function setFields(): array
 *     {
 *         return [
 *             Text::make('title')
 *                 ->rules(['required']),              // fillable(true) by default
 *
 *             Text::make('description')
 *                 ->rules(['nullable']),              // fillable(true) by default
 *
 *             Text::make('internal_notes')
 *                 ->fillable(false)                   // Excluded from mass assignment
 *                 ->onlyOnDetail(),                   // Read-only field
 *
 *             Text::make('computed_field')
 *                 ->virtual(true)                     // Automatically excluded
 *                 ->hideFromIndex(),
 *
 *             FileUploader::make('avatar')            // fillable(true) by default
 *                 ->rules(['image']),                 // Stores file path
 *         ];
 *     }
 * }
 *
 * Result: Framework will generate this fillable array automatically:
 * $fillable = ['title', 'description', 'avatar'];
 *
 * Notice how 'internal_notes' and 'computed_field' are excluded!
 */
