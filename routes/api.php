<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TimesheetController;

Route::controller(AuthController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});

Route::controller(UserController::class)->group(function() {
    Route::get('/user', 'index')->middleware('auth:sanctum');
    Route::post('/user', 'create')->middleware('auth:sanctum');
    Route::get('/user/{id}', 'show')->middleware('auth:sanctum');
    Route::post('/user/update', 'update')->middleware('auth:sanctum');
    Route::post('/user/delete', 'delete')->middleware('auth:sanctum');
});

Route::controller(ProjectController::class)->group(function() {
    Route::get('/project', 'index')->middleware('auth:sanctum');
    Route::post('/project', 'create')->middleware('auth:sanctum');
    Route::get('/project/{id}', 'show')->middleware('auth:sanctum');
    Route::post('/project/update', 'update')->middleware('auth:sanctum');
    Route::post('/project/delete', 'delete')->middleware('auth:sanctum');
});

Route::controller(TimesheetController::class)->group(function() {
    Route::get('/timesheet', 'index')->middleware('auth:sanctum');
    Route::post('/timesheet', 'create')->middleware('auth:sanctum');
    Route::get('/timesheet/{id}', 'show')->middleware('auth:sanctum');
    Route::post('/timesheet/update', 'update')->middleware('auth:sanctum');
    Route::post('/timesheet/delete', 'delete')->middleware('auth:sanctum');
});
