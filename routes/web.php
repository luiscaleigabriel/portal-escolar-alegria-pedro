<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserApprovalController;
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

// Rotas de administração (apenas admin/director)
Route::middleware(['auth', 'role:admin|director'])->prefix('admin')->name('admin.')->group(function () {
    // Aprovação de usuários
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/pending', [UserApprovalController::class, 'pending'])->name('pending');
        Route::get('/{user}', [UserApprovalController::class, 'show'])->name('show');
        Route::post('/{user}/approve', [UserApprovalController::class, 'approve'])->name('approve');
        Route::post('/{user}/reject', [UserApprovalController::class, 'reject'])->name('reject');
        Route::post('/{user}/suspend', [UserApprovalController::class, 'suspend'])->name('suspend');
        Route::get('/', [UserApprovalController::class, 'index'])->name('index');
        Route::get('/stats', [UserApprovalController::class, 'stats'])->name('stats');
    });
});

// Rotas públicas
Route::get('/register/success', function () {
    return view('auth.register-success');
})->name('register.success');

/**
 * Área Autenticada
 */
Route::middleware(['auth'])->group(function () {
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

    // Chat
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::post('/chats', [ChatController::class, 'store'])->name('chats.store');
    Route::post('/chats/{thread}/message', [ChatController::class, 'message'])->name('chats.message');
    Route::get('/chats/{thread}', [ChatController::class, 'show'])->name('chats.show');

    // Reports
    Route::get('/students/{student}/report', [ReportCardController::class, 'generate'])->name('students.report');

    // Admin Routes
    Route::middleware(['role:admin|director'])->group(function () {
        Route::resource('students', StudentController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('turmas', TurmaController::class);
        Route::resource('subjects', SubjectController::class);
    });
});


// Área administrativa
Route::prefix('admin')->middleware(['auth', 'approved', 'role:admin|director'])->group(function () {
    // Dashboard admin
    Route::get('/', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('/stats', [AdminDashboardController::class, 'stats'])
        ->name('admin.stats');

    // Usuários
    Route::prefix('users')->group(function () {
        Route::get('/pending', [UserApprovalController::class, 'pending'])
            ->name('admin.users.pending');
        Route::get('/', [UserApprovalController::class, 'index'])
            ->name('admin.users.index');
        Route::get('/stats', [UserApprovalController::class, 'stats'])
            ->name('admin.users.stats');
        Route::get('/{user}', [UserApprovalController::class, 'show'])
            ->name('admin.users.show');
        Route::post('/{user}/approve', [UserApprovalController::class, 'approve'])
            ->name('admin.users.approve');
        Route::post('/{user}/reject', [UserApprovalController::class, 'reject'])
            ->name('admin.users.reject');
        Route::post('/{user}/suspend', [UserApprovalController::class, 'suspend'])
            ->name('admin.users.suspend');

        // Ações em lote
        Route::post('/batch-approve', [AdminDashboardController::class, 'batchApprove'])
            ->name('admin.users.batch.approve');
        Route::post('/batch-reject', [AdminDashboardController::class, 'batchReject'])
            ->name('admin.users.batch.reject');
    });

    // Chat admin
    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatController::class, 'index'])
            ->name('admin.chat.index');
        Route::post('/', [ChatController::class, 'store'])
            ->name('admin.chat.store');
        Route::get('/{thread}/export', [ChatController::class, 'export'])
            ->name('admin.chat.export');
        Route::delete('/{thread}', [ChatController::class, 'destroy'])
            ->name('admin.chat.destroy');
    });

    // CRUD completo
    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('turmas', TurmaController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('grades', GradeController::class);
    Route::resource('absences', GuardianController::class);
});

// Chat para todos usuários
Route::prefix('chat')->middleware(['auth', 'approved'])->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/', [ChatController::class, 'store'])->name('chat.store');
    Route::post('/threads/{thread}/messages', [ChatController::class, 'message'])
        ->name('chat.message');
});
