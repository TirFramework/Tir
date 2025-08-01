<?php

namespace App\Http\Controllers\Admin;

use Tir\Crud\Controllers\CrudController;

/**
 * MinimalExampleController - Demonstrating Clean Architecture
 *
 * This controller demonstrates how simple CRUD controllers become
 * when using the new clean architecture approach.
 */
class MinimalExampleController extends CrudController
{
    protected function setScaffolder(): string
    {
        return \App\Scaffolders\MinimalExampleScaffolder::class;
    }

    protected function setup()
    {
        $this->onInitQuery(function() {
            return $this->model()->query()->where('is_active', true);
        });

        $this->onSearch(function($query) {
            $search = request()->input('search');
            if ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                      ->orWhere('description', 'like', "%$search%");
                });
            }
            return null; // Use default behavior
        });

        // Custom columns selection
        $this->onColumns(function() {
            return ['id', 'title', 'is_active', 'created_at'];
        });

        // Custom relations loading
        // $this->onRelations(function($query) {
        //     return $query->with(['user:id,name', 'category:id,name']);
        // });

        // Custom pagination
        $this->onPaginate(function($query) {
            return $query->simplePaginate(5); // Use simple pagination with 5 items
        });

    }
}
