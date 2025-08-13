<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Tir\Crud\Controllers\Traits\Crud;
use Tir\Crud\Support\Scaffold\Actions;
use Tir\Crud\Support\Scaffold\ActionType;

/**
 * MinimalExampleController - Demonstrating Clean Access Control
 *
 * This controller demonstrates the improved access control system
 * with clear, intuitive APIs that eliminate confusion.
 */
class MinimalExampleController extends Controller
{
    use Crud;

    protected function setScaffolder(): string
    {
        return \App\Scaffolders\MinimalExampleScaffolder::class;
    }

    protected function setup()
    {
        // Simple: Disable access control for this entire controller
        // $this->accessControlEnabled = false;

        $this->onCheckAccess(function ($action) {
            return false;
        });

        // Index hooks
        $this->onSelect(function ($defaultSelect, $query) {
            $defaultSelect();
            $col = $query->getQuery()->columns;

            $col[] = \DB::raw(value: "CONCAT(title, description) as x");
            $query->select($col);
            return $query;
        });

        $this->onFilter(function ($defaultFilter) {
            return $defaultFilter();
        });

        $this->onSort(function ($defaultSort, $query) {
            return $query->orderBy('id', 'asc');
        });

        $this->onIndexResponse(function ($defaultIndex, $items) {
            $test = [];
            return $defaultIndex();
        });

        // Create/Store hooks
        $this->onFillModelForStore(function ($defaultFill, $model, $request) {
            // You can modify the request data before filling the model
            $data = $request->all();
            $data['title'] = $data['title'] . ' - Modified by hook';
            return $model->fill($data);
        });

        $this->onSaveModel(function ($defaultSave, $model, $request) {
            // You can perform additional operations before saving
            \Log::info('Saving model: ' . $model->title);
            $model->save();
            return $model;
        });

        $this->onStoreRelations(function ($defaultRelations, $request, $model) {
            // Custom logic for relations
            \Log::info('Custom handling for all relations');
            return $defaultRelations();
        });

        $this->onStoreCompleted(function ($defaultCompleted, $model, $request) {
            // After store is completed
            \Log::info('Store completed for model with ID: ' . $model->id);
            return $model;
        });

        // Edit/Update hooks
        $this->onFillModelForUpdate(function ($defaultFill, $model, $request) {
            // You can modify the request data before updating the model
            $data = $request->all();
            $data['description'] = $data['description'] . ' - Updated on ' . date('Y-m-d');
            return $model->fill($data);
        });

        $this->onUpdate(function ($defaultUpdate, $request, $id) {
            // Custom logic for the whole update process
            \Log::info('Custom update process for ID: ' . $id);
            return $defaultUpdate();
        });

        $this->onUpdateCompleted(function ($defaultCompleted, $model, $request) {
            // After update is completed
            \Log::info('Update completed for model with ID: ' . $model->id);
            return $model;
        });
    }

    /**
     * ===================================================================
     * SIMPLIFIED ACCESS CONTROL - THREE SIMPLE OPTIONS!
     * ===================================================================
     *
     * OPTION 1: Disable access control entirely (current)
     * protected function setup()
     * {
     *     $this->accessControlEnabled = false;
     * }
     *
     * OPTION 2: Enable access control with default system checks
     * protected function setup()
     * {
     *     $this->accessControlEnabled = true;
     * }
     *
     * OPTION 3: Enable access control with custom logic
     * protected function setup()
     * {
     *     $this->accessControlEnabled = true;
     *     $this->onCheckAccess(function ($action) {
     *         if ($action === 'destroy') {
     *             return auth()->user()->isAdmin(); // Only admins can delete
     *         }
     *         if ($action === 'store') {
     *             return auth()->user()->canCreate(); // Check create permission
     *         }
     *         return true; // Allow all other actions (index, show, update)
     *     });
     * }
     *
     * That's it! Simple, clear, and intuitive:
     * - Set accessControlEnabled property in setup() to avoid trait conflicts
     * - true = allow access, false = deny access
     * - No more confusing hooks or complex logic!
     */
}
