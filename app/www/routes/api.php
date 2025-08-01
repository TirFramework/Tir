<?php

use App\Http\Controllers\Admin\MinimalExampleController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post("/v1/admin/login", function(){
    return true;
});
Route::get('/v1/admin/mehr-panel', function (Request $request) {
    return (object)['name' => 'Monarchco GmbH', 'username' => 'user'];

});
Route::get('/v1/admin/sidebar', function () {
    return [ (object)
            [
                "name"=> "dashboard",
                "title"=> "Dashboard",
                "link"=> "\/admin\/custom\/dashboard",
                "icon"=> "DashboardOutlined",
                "weight"=> null,
                "badge"=> null,
                "access"=> true,
                "children"=> []
        ]
            ];
})->name('admin.sidebar');

Route::resource('/v1/admin/user',UserController::class)->names('admin.user');
Route::resource('/v1/admin/minimal-example',MinimalExampleController::class)->names('admin.minimal-example');
Route::resource('/v1/admin/sample-model', \App\Http\Controllers\SampleModelController::class)->names('admin.sample-model');
