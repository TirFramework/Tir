<?php

use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/v1/admin/login', [LoginController::class, 'login'])->name('login');
Route::resource('/v1/admin/user', UserController::class)->names('admin.user');
Route::get('/v1/admin/sidebar', function () {

    $menu = [];

    $menu[] = (object)[
        'title' => 'User',
        'link'  => '/admin/user',
        'icon'  => 'DesktopOutlined',
        'name'  => 'user',
    ];


    return collect($menu);
});

Route::get('/v1/admin/mehr-panel', fn () => (object)['name' => config('app.name')]);
