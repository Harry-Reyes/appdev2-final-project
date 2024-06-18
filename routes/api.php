<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
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


// * USERS
Route::apiResource('users', UserController::class)->except('update');

// Search users
Route::post('users/search', [UserController::class, 'search']);

// Edit user
Route::match(['put', 'patch'], 'user', [UserController::class, 'update']);

// Create token or Login user
Route::post('login', [UserController::class, 'login']);

// Destroy token or Logout user
Route::post('logout', [UserController::class, 'logout']);

// * JOBS
Route::apiResource('jobs', JobController::class);

// Search jobs
Route::post('jobs/search', [JobController::class, 'search']);
