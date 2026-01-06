<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


/**
 *
 * Home
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
