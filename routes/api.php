<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Create user or Register user
Route::post('users', [UserController::class, 'store']);

// Create token or Login user
Route::post('login', [UserController::class, 'login']);

// For getting users or a user
Route::apiResource('jobs', JobController::class);

// Search jobs
Route::post('jobs/search', [JobController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {
    // * USERS
    // Get all users
    Route::get('users', [UserController::class, 'index']);

    // Get a user
    Route::get('users/{user}', [UserController::class, 'show']);

    // Search users
    Route::post('users/search', [UserController::class, 'search']);

    // Delete user only accessible by admin token
    Route::middleware('admin')->delete( 'users/{user}', [UserController::class, 'destroy']);

    // Edit user
    Route::match(['put', 'patch'], 'user', [UserController::class, 'update']);

    // Destroy token or Logout user
    Route::post('logout', [UserController::class, 'logout']);

    // * JOBS
    // Create job
    Route::post('jobs', [JobController::class, 'store']);

    // Edit job
    Route::match(['put', 'patch'], 'jobs/{job}', [JobController::class, 'update']);

    // Delete job
    Route::delete('jobs/{job}', [JobController::class, 'destroy']);
});

