<?php

namespace App\Http\Controllers\Admin;

use Tir\Crud\Controllers\Crud;
use App\Http\Controllers\Controller;


/**
 * MinimalExampleController - Demonstrating Clean Architecture
 *
 * This controller demonstrates how simple CRUD controllers become
 * when using the new clean architecture approach.
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

}
