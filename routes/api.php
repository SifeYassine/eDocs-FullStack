<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\roles\RoleController;
use App\Http\Controllers\api\permissions\PermissionController;
use App\Http\Controllers\api\permission_user\PermissionUserController;
use App\Http\Middleware\PermissionMiddleware;
use App\Http\Controllers\api\users\UserController;
use App\Http\Controllers\api\categories\CategoryController;
use App\Http\Controllers\api\documents\DocumentController;

// Register & login routes
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Logout route
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Roles Routes
    Route::post('roles/create', [RoleController::class, 'create'])->middleware(PermissionMiddleware::class . ':Create a Role');
    Route::get('roles/index', [RoleController::class, 'index'])->middleware(PermissionMiddleware::class . ':List Roles');
    Route::put('roles/update/{id}', [RoleController::class, 'update'])->middleware(PermissionMiddleware::class . ':Update a Role');
    Route::delete('roles/delete/{id}', [RoleController::class, 'delete'])->middleware(PermissionMiddleware::class . ':Delete a Role');

    // Permissions Routes
    Route::post('permissions/create', [PermissionController::class, 'create'])->middleware(PermissionMiddleware::class . ':Create a Permission');
    Route::get('permissions/index', [PermissionController::class, 'index'])->middleware(PermissionMiddleware::class . ':List Permissions');
    Route::put('permissions/update/{id}', [PermissionController::class, 'update'])->middleware(PermissionMiddleware::class . ':Update a Permission');
    Route::delete('permissions/delete/{id}', [PermissionController::class, 'delete'])->middleware(PermissionMiddleware::class . ':Delete a Permission');

    // Permission_User routes
    Route::post('users/{userId}/permissions/{permissionId}/assign', [PermissionUserController::class, 'assignPermissionToUser']);
    Route::get('users/{userId}/permissions/index', [PermissionUserController::class, 'getPermissionsAssignedToUser']);
    Route::delete('users/{userId}/permissions/{permissionId}/revoke', [PermissionUserController::class, 'revokePermissionFromUser']);

    // Users Routes
    Route::get('users/index', [UserController::class, 'index'])->middleware(PermissionMiddleware::class . ':List Users');
    Route::get('users/get_profile', [UserController::class, 'getMyProfile']);
    Route::put('users/update/{id}', [UserController::class, 'update'])->middleware(PermissionMiddleware::class . ':Assign Role to User');
    Route::put('users/edit_profile', [UserController::class, 'editMyProfile']);
    Route::delete('users/delete/{id}', [UserController::class, 'delete'])->middleware(PermissionMiddleware::class . ':Delete a User');

    // Categories routes
    Route::post('categories/create', [CategoryController::class, 'create']);
    Route::get('categories/index', [CategoryController::class, 'index']);
    Route::put('categories/update/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/delete/{id}', [CategoryController::class, 'delete']);

    // Documents routes
    Route::post('documents/create', [DocumentController::class, 'create']);
    Route::get('documents/index', [DocumentController::class, 'index']);
    Route::put('documents/update/{id}', [DocumentController::class, 'update']);
    Route::delete('documents/delete/{id}', [DocumentController::class, 'delete']);
});
