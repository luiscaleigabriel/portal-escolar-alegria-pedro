<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportCardController;
use Illuminate\Support\Facades\Route;


/**
 *
 * Home
 */
Route::get('/', [HomeController::class, 'index'])->name('site');



Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

/**
 *
 * Autênticação
 */
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);


/**
 *
 * Dashboard
 */
Route::get('/dashboard', function () {
    return view('dashboard', [
        'students' => \App\Models\Student::count(),
        'teachers' => \App\Models\Teacher::count(),
        'classes' => \App\Models\Turma::count(),
        'messages' => \App\Models\Message::count()
    ]);
})->middleware('auth');

/**
 *
 * Auth
 */
Route::middleware(['auth'])->group(function () {
    // Grades
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/{grade}', [GradeController::class, 'show'])->name('grades.show');
    Route::put('/grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');

    // Absences
    Route::get('/absences', [AbsenceController::class, 'index'])->name('absences.index');
    Route::post('/absences', [AbsenceController::class, 'store'])->name('absences.store');
    Route::get('/absences/{absence}', [AbsenceController::class, 'show'])->name('absences.show');
    Route::put('/absences/{absence}', [AbsenceController::class, 'update'])->name('absences.update');
    Route::delete('/absences/{absence}', [AbsenceController::class, 'destroy'])->name('absences.destroy');
});


/**
 *
 * Chat
 */
Route::middleware('auth')->group(function () {
    Route::get('/chats', [ChatController::class, 'index']); // Listar threads
    Route::post('/chats', [ChatController::class, 'store']); // Criar thread
    Route::post('/chats/{thread}/message', [ChatController::class, 'message']); // Enviar mensagem
    Route::get('/chats/{thread}', [ChatController::class, 'show']); // Ver thread
});


/**
 *
 * Gerar PDF
 */
Route::middleware('auth')->get('/students/{student}/report', [ReportCardController::class, 'generate']);
