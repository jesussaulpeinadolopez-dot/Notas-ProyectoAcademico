<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tareas', [TaskController::class, 'store']);
    Route::get('/tareas', [TaskController::class, 'index']);
    Route::put('/tareas/{id}', [TaskController::class, 'update']);
    Route::delete('/tareas/{id}', [TaskController::class, 'destroy']);
});