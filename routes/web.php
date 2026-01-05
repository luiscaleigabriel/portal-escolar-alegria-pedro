<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


/**
 *
 * Auth
 */
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/register', [UserController::class, 'index'])->name('user.index');
    Route::post('/register', [UserController::class, 'store'])->name('user.store');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');




/**
 *
 * Tasks
 */
Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('task.create');
    Route::post('/tasks/create', [TaskController::class, 'store'])->name('task.store');
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('task.edit');
    Route::put('/tasks/{id}/update', [TaskController::class, 'update'])->name('task.update');
    Route::delete('/tasks/{id}/delete', [TaskController::class, 'destroy'])->name('task.destroy');
});

