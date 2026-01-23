<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

/**
 * Página Inicial
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

/**
 * Autenticação
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Rotas de status de conta
Route::middleware(['auth'])->group(function () {
    Route::get('/pending-approval', function () {
        return view('auth.pending-approval');
    })->name('pending-approval');

    Route::get('/account/rejected', function () {
        return view('auth.account-rejected');
    })->name('account.rejected');

    Route::get('/account/suspended', function () {
        return view('auth.account-suspended');
    })->name('account.suspended');
});

// Rotas públicas
Route::get('/register/success', function () {
    return view('auth.register-success');
})->name('register.success');

/**
 * Área Autenticada (Todos usuários aprovados)
 */
Route::middleware(['auth', 'approved'])->group(function () {
    // Dashboard principal com redirecionamento por role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    // Chat para todos usuários
    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chat.index');
        Route::post('/', [ChatController::class, 'store'])->name('chat.store');
        Route::post('/threads/{thread}/messages', [ChatController::class, 'message'])
            ->name('chat.message');
        Route::get('/{thread}', [ChatController::class, 'show'])->name('chats.show');
    });

    // Reports
    Route::get('/students/{student}/report', [ReportCardController::class, 'generate'])->name('students.report');
});

/**
 * Área Administrativa (Somente admin/director)
 */
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin|director'])->group(function () {
    // Dashboard admin
    Route::get('/', [AdminDashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/stats', [AdminDashboardController::class, 'stats'])
        ->name('stats');

    // Gestão de Usuários
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

        // Aprovação de usuários
        Route::get('/pending', [UserApprovalController::class, 'pending'])->name('pending');
        Route::post('/{user}/approve', [UserController::class, 'approve'])->name('approve');
        Route::post('/{user}/reject', [UserController::class, 'reject'])->name('reject');
        Route::post('/{user}/suspend', [UserController::class, 'suspend'])->name('suspend');
        Route::post('/{user}/activate', [UserController::class, 'activate'])->name('activate');

        // Ações em massa
        Route::post('/bulk-actions', [UserController::class, 'bulkActions'])->name('bulk-actions');
        Route::post('/import', [UserController::class, 'import'])->name('import');
        Route::get('/export', [UserController::class, 'export'])->name('export');
    });

    // Gestão de Estudantes
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [AdminStudentController::class, 'index'])->name('index');
        Route::get('/create', [AdminStudentController::class, 'create'])->name('create');
        Route::post('/', [AdminStudentController::class, 'store'])->name('store');
        Route::get('/{student}', [AdminStudentController::class, 'show'])->name('show');
        Route::get('/{student}/edit', [AdminStudentController::class, 'edit'])->name('edit');
        Route::put('/{student}', [AdminStudentController::class, 'update'])->name('update');
        Route::delete('/{student}', [AdminStudentController::class, 'destroy'])->name('destroy');

        // Ações em massa
        Route::post('/bulk-actions', [StudentController::class, 'bulkActions'])->name('bulk-actions');
        Route::post('/import', [StudentController::class, 'import'])->name('import');
        Route::get('/export', [StudentController::class, 'export'])->name('export');

        // Gestão de responsáveis
        Route::post('/{student}/guardian', [StudentController::class, 'attachGuardian'])->name('guardian.attach');
        Route::delete('/{student}/guardian/{guardian}', [StudentController::class, 'detachGuardian'])->name('guardian.detach');
    });

    // Chat admin (com funcionalidades extras)
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])
            ->name('index');
        Route::post('/', [ChatController::class, 'store'])
            ->name('store');
        Route::get('/{thread}/export', [ChatController::class, 'export'])
            ->name('export');
        Route::delete('/{thread}', [ChatController::class, 'destroy'])
            ->name('destroy');
    });

    // CRUD completo de todas as entidades
    Route::resource('teachers', TeacherController::class)->except(['show']);
    Route::resource('turmas', TurmaController::class)->except(['show']);
    Route::resource('subjects', SubjectController::class)->except(['show']);
    Route::resource('grades', GradeController::class)->except(['show']);
    Route::resource('absences', AbsenceController::class)->except(['show']);
    Route::resource('guardians', GuardianController::class)->except(['show']);
});

/**
 * Rotas específicas para visualização (acessíveis por mais perfis)
 */
Route::middleware(['auth', 'approved'])->group(function () {
    // Visualização de detalhes (acessível por admin, director e professores relacionados)
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');
    Route::get('/turmas/{turma}', [TurmaController::class, 'show'])->name('turmas.show');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
    Route::get('/grades/{grade}', [GradeController::class, 'show'])->name('grades.show');
    Route::get('/absences/{absence}', [AbsenceController::class, 'show'])->name('absences.show');
    Route::get('/guardians/{guardian}', [GuardianController::class, 'show'])->name('guardians.show');
});
