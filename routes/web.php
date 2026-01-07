<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


/**
 *
 * Home
 */
Route::get('/', [HomeController::class, 'index'])->name('home');


/**
 *
 * Auth
 */
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
