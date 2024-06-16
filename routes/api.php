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

Route::apiResource('users', UserController::class);

// Create token
Route::post('login', [UserController::class, 'login']);

Route::apiResource('jobs', JobController::class);

Route::middleware('auth:sanctum')->group(function () {
    // Retrieving users
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::post('users/search', [UserController::class, 'search']);

    // Delete only accessible by admin token
    Route::middleware('admin')->delete( 'users/{user}', [UserController::class, 'destroy']);

    // Single user
    Route::get('/user', function (Request $request) {return $request->user();});
    Route::match(['put', 'patch'], 'user/edit', [UserController::class, 'update']);

    // Destroy token
    Route::post('logout', [UserController::class, 'logout']);
});

