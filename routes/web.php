<?php

use Illuminate\Support\Facades\Route;
use Tir\Crud\Support\Scaffold\Fields\Select;
use Tir\Crud\Support\Scaffold\Fields\Text;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('crudTest', function (){
    $user = new \Tir\User\Entities\User();

    $user->fields()->add([
        Text::make('test'),

        Text::make('last_name')
            ->hideOnIndex(function () {
                return false;
            }),

        Select::make('users')
                ->data([1,2,3])

    ]);


//    $crud = \Tir\Crud\Support\Scaffold\Crud::getCrud();
//
//    $crud->setModel(\App\Models\User::class);
//
//     $crud->getModel();




    return $user->fields()->get();

});


Route::get('/', function () {
    return view('dashboard');
});

