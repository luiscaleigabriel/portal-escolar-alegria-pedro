<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard;
use App\Livewire\Dashboard\AdminDashboard;
use App\Livewire\Dashboard\ParentDashboard;
use App\Livewire\Dashboard\SecretaryDashboard;
use App\Livewire\Dashboard\StudentDashboard;
use App\Livewire\Dashboard\TeacherDashboard;
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Rotas autenticadas
Route::middleware(['auth', 'approved', 'active'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/logout', function () {
        auth()->logout();
        return redirect('/login');
    })->name('logout');

    // Dashboards específicos
    Route::get('/student/dashboard', StudentDashboard::class)->name('student.dashboard');
    Route::get('/teacher/dashboard', TeacherDashboard::class)->name('teacher.dashboard');
    Route::get('/parent/dashboard', ParentDashboard::class)->name('parent.dashboard');
    Route::get('/secretary/dashboard', SecretaryDashboard::class)->name('secretary.dashboard');
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');

    // Rotas de recursos
    // Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
    //     Route::get('/grades', [StudentController::class, 'grades'])->name('grades');
    //     Route::get('/subjects', [StudentController::class, 'subjects'])->name('subjects');
    //     Route::get('/tasks', [StudentController::class, 'tasks'])->name('tasks');
    //     // ... outras rotas
    // });

    // Redirecionamento baseado no role
    Route::get('/', function () {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'secretary' => redirect()->route('secretary.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            default => redirect()->route('dashboard'),
        };
    });
});
