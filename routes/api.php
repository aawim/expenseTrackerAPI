<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\API\ExpenseController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\API\PaymentMethodController;
use App\Http\Controllers\Api\UserPermissionController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);




Route::middleware('auth:sanctum')->group(function () {

    //  Auth Routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('refresh-token', 'refresh')->name('refresh');
        Route::get('user', 'user');
        Route::post('logout', 'logout')->name('logout');
    });

    //  Common Data
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('payment-methods', [PaymentMethodController::class, 'index']);

    //  User Management
    Route::apiResource('users', UserController::class);

    //  Expense Management
    Route::apiResource('expenses', ExpenseController::class);

    //  Role Management
    Route::prefix('roles')->controller(RoleController::class)
        ->group(function () {
            Route::get('/', 'index')->name('roles.index');
            Route::post('/', 'store')->name('roles.store');
            Route::get('/{role}', 'show')->name('roles.show');
            Route::patch( '/{role}', 'update')->name('roles.update');
            Route::delete('/{role}', 'destroy')->name('roles.destroy');
            Route::post('/{role}/permissions', 'syncPermissions');
        });

    //  Permission Management
    Route::apiResource('permissions', PermissionController::class);

    // User Permissions
    Route::prefix('user-permissions')->controller(UserPermissionController::class)
        ->group(function () {
            Route::get('/{permission}', 'index')->name('user-permissions.index');
            Route::patch('/{permission}', 'update')->name('user-permissions.update');
        });

});
