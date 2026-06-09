<?php

use App\Http\Controllers\Admin\MinimalExampleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FlexibleFieldsController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SidebarController;

// Authentication endpoints (no auth required)
Route::post('/v1/admin/login', [AuthController::class, 'login'])->name('auth.login');

Route::get('/v1/admin/mehr-panel', function (Request $request) {
    return (object)['name' => 'Tir CRUD Demo', 'username' => 'user'];
});

// Protected routes - require authentication
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth endpoints
    Route::post('/v1/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/v1/auth/profile', [AuthController::class, 'profile'])->name('auth.profile');

    // Admin Panel Routes (Dashboard & Navigation)
    Route::prefix('v1/admin')->group(function () {
        // Dashboard & Navigation
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/sidebar', [SidebarController::class, 'sidebar'])->name('admin.sidebar');
        Route::get('/topbar', [SidebarController::class, 'topbar'])->name('admin.topbar');

        // CRUD Resources
        Route::resource('/user', UserController::class)->names('admin.user');
        Route::resource('/user-role', UserRoleController::class)->names('admin.user-role');
        Route::resource('/category', CategoryController::class)->names('admin.category');
        Route::resource('/sample-model', \App\Http\Controllers\SampleModelController::class)->names('admin.sample-model');
        Route::resource('/minimal-example', MinimalExampleController::class)->names('admin.minimal-example');
        Route::resource('/flexible-fields', FlexibleFieldsController::class)->names('admin.flexible-fields');
    });
});
