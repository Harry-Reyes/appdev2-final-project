<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationController;

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

Route::post('jobs/{job}/apply', [JobController::class, 'apply']);

// * JOB APPLICATIONS
Route::apiResource('applications', ApplicationController::class)->only(['index', 'destroy']);

Route::controller(ApplicationController::class)->prefix('applications')->group(function () {
    Route::get('my', 'check_applications');

    Route::get('applicants', 'check_applicants');

    Route::match(['put', 'patch'], '{application}/accept', 'accept');

    Route::match(['put', 'patch'], '{application}/decline', 'decline');

    Route::match(['put', 'patch'], '{application}/undo', 'undo');
});

