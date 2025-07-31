<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MinimalExampleController;

/**
 * Minimal Example Routes - Demonstrating Clean Architecture
 *
 * These routes show how simple CRUD routing becomes with the new approach.
 * All standard CRUD operations are handled automatically by CrudController.
 */
Route::resource('minimal-example', MinimalExampleController::class);

/**
 * Expected Generated Routes:
 *
 * GET    /minimal-example          MinimalExampleController@index
 * GET    /minimal-example/create   MinimalExampleController@create
 * POST   /minimal-example          MinimalExampleController@store
 * GET    /minimal-example/{id}     MinimalExampleController@show
 * GET    /minimal-example/{id}/edit MinimalExampleController@edit
 * PUT    /minimal-example/{id}     MinimalExampleController@update
 * DELETE /minimal-example/{id}     MinimalExampleController@destroy
 *
 * All these endpoints automatically:
 * 1. Use the auto-generated $fillable array
 * 2. Respect field-level fillable(false) settings
 * 3. Handle validation rules from scaffolder
 * 4. Support all field types and their behaviors
 */
