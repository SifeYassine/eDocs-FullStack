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
use App\Http\Controllers\api\posts\PostController;

// Register & login routes
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Logout route
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Roles Routes with "Manage Roles" Permission Middleware
    Route::middleware([PermissionMiddleware::class . ':Manage Roles'])->group(function () {
        Route::post('roles/create', [RoleController::class, 'create']);
        Route::get('roles/index', [RoleController::class, 'index']);
        Route::put('roles/update/{id}', [RoleController::class, 'update']);
        Route::delete('roles/delete/{id}', [RoleController::class, 'delete']);
    });

    // Permissions Routes with "Manage Permissions" Permission Middleware
    Route::middleware([PermissionMiddleware::class . ':Manage Permissions'])->group(function () {
        Route::post('permissions/create', [PermissionController::class, 'create']);
        Route::get('permissions/index', [PermissionController::class, 'index']);
        Route::put('permissions/update/{id}', [PermissionController::class, 'update']);
        Route::delete('permissions/delete/{id}', [PermissionController::class, 'delete']);

        // Permission_User routes
        Route::post('users/{userId}/permissions/{permissionId}/assign', [PermissionUserController::class, 'assignPermissionToUser']);
        Route::delete('users/{userId}/permissions/{permissionId}/revoke', [PermissionUserController::class, 'revokePermissionFromUser']);
    });

    // Users Routes
    Route::middleware([PermissionMiddleware::class . ':Manage Users'])->group(function () {
        Route::get('users/index', [UserController::class, 'index']);
        Route::put('users/update/{id}', [UserController::class, 'update']);
        Route::delete('users/delete/{id}', [UserController::class, 'delete']);
    });

    // Index Permissions_User Route
    Route::get('users/{userId}/permissions/index', [PermissionUserController::class, 'getPermissionsAssignedToUser']);

    // Personal Profile Routes
    Route::get('users/get_profile', [UserController::class, 'getMyProfile']);
    Route::put('users/edit_profile', [UserController::class, 'editMyProfile']);


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

    // Posts routes
    Route::post('posts/create', [PostController::class, 'create']);
    Route::get('posts/index', [PostController::class, 'index']);
    Route::delete('posts/delete/{id}', [PostController::class, 'delete']);
});
