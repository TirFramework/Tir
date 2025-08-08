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


        $this->onSelect(function ($defaultSelect, $query) {
            $defaultSelect();
            $col = $query->getQuery()->columns;

            $col[] = \DB::raw(value: "CONCAT(title, description) as x");
            $query->select($col);
            return $query;
        });

        // $this->onSearch(function ($defaultSearch, $query) {
        //     $query = $defaultSearch();

        //     // Add a where condition on the calculated column 'x'
        //     // Using havingRaw since 'x' is a calculated column
        //     $searchTerm = request()->input('search');
        //     if ($searchTerm) {
        //         $query->orHavingRaw('x LIKE ?', ['%' . $searchTerm . '%']);
        //     }

        //     return $query;
        // });


        $this->onFilter(function ($defaultFilter) {
            return $defaultFilter();
        });

        $this->onSort(function ($defaultSort, $query) {
            return $query->orderBy('id', 'asc');
        });

        // $this->onPaginate(function ($defaultPaginate, $query) {
        //     return $query->paginate(1);
        // });

        $this->onIndexResponse(function ($defaultIndex, $items) {
            $test = [];
            return $defaultIndex();
        });



    }
}



