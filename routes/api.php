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


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/payment-methods', [PaymentMethodController::class, 'index']);
    

    Route::post('refresh-token', [AuthController::class, 'refresh'])
        ->name('refresh');
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('users', UserController::class);
    Route::apiResource('expenses', ExpenseController::class);
// List all roles (GET)
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');

// Create a new role (POST)
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');

// Show a specific role (GET)
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');

// Update a role (PUT/PATCH)
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::patch('roles/{role}', [RoleController::class, 'update'])->name('roles.update');

// Delete a role (DELETE)
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::apiResource('permissions', PermissionController::class);
    Route::post('roles/{role}/permissions', [RoleController::class, 'syncPermissions']);
 
    Route::get('user-permissions/{id}', [UserPermissionController::class, 'user-permissions.index']);
    Route::patch('user-permissions/{id}', [UserPermissionController::class, 'user-permissions.update']);

});
