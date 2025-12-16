<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;

Route::get('/', [TaskController::class, 'create'])->name('tasks.create');
Route::post('/task', [TaskController::class, 'store'])->name('tasks.store');
Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');

Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
