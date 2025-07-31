<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampleModelController;

/*
|--------------------------------------------------------------------------
| Sample Model Routes
|--------------------------------------------------------------------------
|
| These routes demonstrate the complete CRUD setup for the SampleModel.
| The routes follow RESTful conventions and include additional custom
| endpoints for advanced functionality.
|
*/

Route::middleware(['auth:sanctum'])->group(function () {

    // =================================================================
    // STANDARD CRUD ROUTES
    // =================================================================

    // These routes are automatically handled by CrudController:
    // GET /sample-models - List all items with pagination, search, filter
    // GET /sample-models/create - Get create form scaffold
    // POST /sample-models - Store new item with validation
    // GET /sample-models/{id} - Show single item details
    // GET /sample-models/{id}/edit - Get edit form with current data
    // PUT/PATCH /sample-models/{id} - Update existing item
    // DELETE /sample-models/{id} - Soft delete item

    Route::resource('sample-models', SampleModelController::class);

    // =================================================================
    // ADDITIONAL CRUD ROUTES
    // =================================================================

    // Data endpoint for DataTables/listings with advanced filtering
    Route::get('sample-models-data', [SampleModelController::class, 'data'])
        ->name('sample-models.data');

    // Restore soft-deleted item
    Route::post('sample-models/{id}/restore', [SampleModelController::class, 'restore'])
        ->name('sample-models.restore');

    // Permanently delete item (force delete)
    Route::delete('sample-models/{id}/force', [SampleModelController::class, 'forceDestroy'])
        ->name('sample-models.force-destroy');

    // Get trashed/soft-deleted items
    Route::get('sample-models-trash', [SampleModelController::class, 'trash'])
        ->name('sample-models.trash');

    // =================================================================
    // CUSTOM ENDPOINTS
    // =================================================================

    // Bulk operations on multiple items
    Route::post('sample-models/bulk', [SampleModelController::class, 'bulkAction'])
        ->name('sample-models.bulk');

    // Get statistics and analytics
    Route::get('sample-models/statistics', [SampleModelController::class, 'statistics'])
        ->name('sample-models.statistics');

    // Export data in various formats
    Route::get('sample-models/export', [SampleModelController::class, 'export'])
        ->name('sample-models.export');

    // =================================================================
    // SELECT/AUTOCOMPLETE ROUTES
    // =================================================================

    // For Select fields with dynamic data loading
    Route::get('sample-models/select', [SampleModelController::class, 'select'])
        ->name('sample-models.select');

    // For parent-child relationship dropdown
    Route::get('sample-models/parents', function () {
        return \App\Models\SampleModel::select('id as value', 'title as label')
            ->whereNull('parent_id') // Only top-level items
            ->where('is_active', true)
            ->orderBy('title')
            ->get();
    })->name('sample-models.parents');

    // For category autocomplete
    Route::get('sample-models/categories', function () {
        $search = request('search', '');
        return \App\Models\SampleModel::select('category as value', 'category as label')
            ->whereNotNull('category')
            ->when($search, function ($query, $search) {
                return $query->where('category', 'like', "%{$search}%");
            })
            ->groupBy('category')
            ->orderBy('category')
            ->get();
    })->name('sample-models.categories');

});

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// Public API for published items
Route::get('api/sample-models/published', function () {
    return \App\Models\SampleModel::with(['user:id,name'])
        ->where('is_published', true)
        ->where('is_active', true)
        ->where('status', 'published')
        ->orderBy('is_featured', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(20);
})->name('api.sample-models.published');

// Public API for single published item
Route::get('api/sample-models/{slug}', function ($slug) {
    return \App\Models\SampleModel::with(['user:id,name', 'parent:id,title'])
        ->where('slug', $slug)
        ->where('is_published', true)
        ->where('is_active', true)
        ->firstOrFail();
})->name('api.sample-models.show');

/*
|--------------------------------------------------------------------------
| Route Model Binding Customization
|--------------------------------------------------------------------------
*/

// Use slug for public routes, ID for admin routes
Route::bind('sampleModel', function ($value) {
    // If it's numeric, assume it's an ID (for admin routes)
    if (is_numeric($value)) {
        return \App\Models\SampleModel::findOrFail($value);
    }

    // Otherwise, assume it's a slug (for public routes)
    return \App\Models\SampleModel::where('slug', $value)->firstOrFail();
});
