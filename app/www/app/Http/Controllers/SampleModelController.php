<?php

namespace App\Http\Controllers;

use Tir\Crud\Controllers\CrudController;
use App\Scaffolders\SampleModelScaffolder;

/**
 * SampleModelController - Example controller demonstrating CRUD operations
 *
 * This controller showcases the clean separation between Controller, Model, and Scaffolder
 * in the Tir CRUD framework. It demonstrates the minimal code required to implement
 * full CRUD functionality.
 *
 * Features included:
 * - Complete CRUD operations (Create, Read, Update, Delete)
 * - Automatic validation based on scaffolder rules
 * - File upload handling
 * - JSON field management
 * - Relationship handling
 * - Soft delete support
 * - Access control integration
 *
 * All business logic is properly separated:
 * - Model: Handles data persistence and relationships
 * - Scaffolder: Defines UI structure and validation rules
 * - Controller: Orchestrates between model and scaffolder
 *
 * @package App\Http\Controllers
 */
class SampleModelController extends CrudController
{
    /**
     * Define which scaffolder this controller uses
     *
     * This is the only required method for a CRUD controller.
     * The scaffolder defines all the fields, validation rules,
     * and UI configuration for this resource.
     *
     * @return string Scaffolder class name
     */
    protected function setScaffolder(): string
    {
        return SampleModelScaffolder::class;
    }

    /**
     * Optional: Customize the request validation class
     *
     * If you need custom validation logic beyond what the scaffolder provides,
     * you can create a custom FormRequest class and return its name here.
     *
     * @return string Custom FormRequest class name (optional)
     */
    public function setRequest(): string
    {
        // return SampleModelRequest::class; // If you have custom validation
        return ''; // Use default CrudRequest with scaffolder rules
    }

    /**
     * Optional: Customize the response class
     *
     * If you need custom response formatting, you can create a custom
     * response class and return its name here.
     *
     * @return string Custom response class name (optional)
     */
    public function setResponse(): string
    {
        // return SampleModelResponse::class; // If you have custom responses
        return parent::setResponse(); // Use default CrudResponse
    }

    /**
     * Optional: Additional initialization logic
     *
     * This method is called after the scaffolder and model are initialized.
     * You can use it to add middleware, set additional properties, etc.
     *
     * @return void
     */
    protected function crudInit(): void
    {
        // Example: Add custom middleware
        // $this->middleware('can:manage-samples');

        // Example: Set additional properties
        // $this->someProperty = 'some value';

        // Example: Add custom validation rules globally
        // request()->merge(['additional_validation' => true]);

        parent::crudInit();
    }

    // =================================================================
    // OPTIONAL: Custom endpoint methods
    //
    // The parent CrudController already provides all standard CRUD endpoints:
    // - index() - List all items with pagination and filtering
    // - create() - Show create form scaffold
    // - store() - Store new item with validation
    // - show() - Show single item details
    // - edit() - Show edit form with current data
    // - update() - Update existing item with validation
    // - destroy() - Soft delete item
    //
    // You can override any of these methods if you need custom behavior.
    // =================================================================

    /**
     * Example: Custom endpoint for bulk operations
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkAction(\Illuminate\Http\Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        switch ($action) {
            case 'publish':
                $this->model()::whereIn('id', $ids)->update(['is_published' => true]);
                break;
            case 'unpublish':
                $this->model()::whereIn('id', $ids)->update(['is_published' => false]);
                break;
            case 'feature':
                $this->model()::whereIn('id', $ids)->update(['is_featured' => true]);
                break;
            case 'unfeature':
                $this->model()::whereIn('id', $ids)->update(['is_featured' => false]);
                break;
            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }

        return response()->json([
            'success' => true,
            'message' => "Bulk {$action} completed successfully",
            'affected_count' => count($ids)
        ]);
    }

    /**
     * Example: Custom endpoint for statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        $stats = [
            'total' => $this->model()::count(),
            'published' => $this->model()::where('is_published', true)->count(),
            'featured' => $this->model()::where('is_featured', true)->count(),
            'by_status' => $this->model()::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status'),
            'by_type' => $this->model()::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            'recent' => $this->model()::latest()->take(5)->get(['id', 'title', 'created_at']),
        ];

        return response()->json($stats);
    }

    /**
     * Example: Custom endpoint for exporting data
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function export(\Illuminate\Http\Request $request)
    {
        $format = $request->input('format', 'csv');

        switch ($format) {
            case 'csv':
                return $this->exportCsv();
            case 'json':
                return $this->exportJson();
            default:
                return response()->json(['error' => 'Unsupported format'], 400);
        }
    }

    /**
     * Export data as CSV
     */
    private function exportCsv()
    {
        $filename = 'sample-models-' . date('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');

            // CSV headers
            fputcsv($handle, [
                'ID', 'Title', 'Status', 'Type', 'Is Published',
                'Is Featured', 'Price', 'Rating', 'Created At'
            ]);

            // CSV data
            $this->model()::chunk(1000, function ($items) use ($handle) {
                foreach ($items as $item) {
                    fputcsv($handle, [
                        $item->id,
                        $item->title,
                        $item->status,
                        $item->type,
                        $item->is_published ? 'Yes' : 'No',
                        $item->is_featured ? 'Yes' : 'No',
                        $item->price,
                        $item->rating,
                        $item->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export data as JSON
     */
    private function exportJson()
    {
        $data = $this->model()::with(['user', 'parent'])->get();

        $filename = 'sample-models-' . date('Y-m-d-H-i-s') . '.json';

        return response()->json($data)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
