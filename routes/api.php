<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\roles\RoleController;
use App\Http\Controllers\api\users\UserController;
use App\Http\Controllers\api\categories\CategoryController;
use App\Http\Controllers\api\documents\DocumentController;

// Register & login routes
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Logout route
    Route::post('auth/logout', [AuthController::class, 'logout']);

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
