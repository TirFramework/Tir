<?php

namespace App\Http\Controllers\Admin;

use Tir\Crud\Controllers\CrudController;

/**
 * Simple Example - Using Stable Interface (Recommended)
 *
 * This is the simplest and most future-proof approach.
 * Extending CrudController gives you all CRUD functionality automatically.
 */
class SimpleExampleController extends CrudController
{
    protected function setScaffolder(): string
    {
        return \App\Scaffolders\MinimalExampleScaffolder::class;
    }

    protected function setup()
    {
        // All the same hooks work as before!
        $this->onSelect(function ($defaultSelect, $query) {
            $defaultSelect();
            $col = $query->getQuery()->columns;
            $col[] = \DB::raw("CONCAT(title, description) as combined");
            $query->select($col);
            return $query;
        });

        $this->onStoreCompleted(function ($defaultCompleted, $model, $request) {
            \Log::info('New record created: ' . $model->title);
            return $model;
        });
    }
}
