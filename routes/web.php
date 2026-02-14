<?php

use App\Livewire\Admin\{Backup, Logs, Settings, System, Users};
use App\Livewire\Auth\{Login, Register, ForgotPassword, ResetPassword};
use App\Livewire\Dashboard\{AdminDashboard, Index as DashboardIndex, ParentDashboard, SecretaryDashboard, StudentDashboard, TeacherDashboard};
use App\Livewire\Student\{Grades, Profile, Subjects, Tasks};
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
    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        session()->flush();
        return redirect()->route('login')->with('success', 'Sessão terminada com sucesso.');
    })->name('logout');

    // Dashboard principal (redireciona para dashboard específico)
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');

    // Dashboards específicos (acessíveis apenas com permissões)
    Route::middleware('can:access-student-dashboard')->get('/student/dashboard', StudentDashboard::class)->name('student.dashboard');
    Route::middleware('can:access-teacher-dashboard')->get('/teacher/dashboard', TeacherDashboard::class)->name('teacher.dashboard');
    Route::middleware('can:access-parent-dashboard')->get('/parent/dashboard', ParentDashboard::class)->name('parent.dashboard');
    Route::middleware('can:access-secretary-dashboard')->get('/secretary/dashboard', SecretaryDashboard::class)->name('secretary.dashboard');
    Route::middleware('can:access-admin-dashboard')->get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');

    // Rotas para estudantes
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function() {
        Route::get('/grades', Grades::class)->name('grades');
        Route::get('/subjects', Subjects::class)->name('subjects');
        Route::get('/tasks', Tasks::class)->name('tasks');
        Route::get('/profile', Profile::class)->name('profile');
        // Route::get('/timetable', \App\Livewire\Student\Timetable::class)->name('timetable')->missing(function() {
        //     return redirect()->route('student.dashboard')->with('error', 'Página em construção');
        // });
        // Route::get('/attendances', \App\Livewire\Student\Attendances::class)->name('attendances')->missing(function() {
        //     return redirect()->route('student.dashboard')->with('error', 'Página em construção');
        // });
    });

    // Rotas para professores
    // Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function() {
    //     Route::get('/classes', \App\Livewire\Teacher\Classes::class)->name('classes')->missing(fn() => redirect()->route('teacher.dashboard'));
    //     Route::get('/grades', \App\Livewire\Teacher\Grades::class)->name('grades')->missing(fn() => redirect()->route('teacher.dashboard'));
    //     Route::get('/tasks', \App\Livewire\Teacher\Tasks::class)->name('tasks')->missing(fn() => redirect()->route('teacher.dashboard'));
    //     Route::get('/attendance', \App\Livewire\Teacher\Attendance::class)->name('attendance')->missing(fn() => redirect()->route('teacher.dashboard'));
    //     Route::get('/students', \App\Livewire\Teacher\Students::class)->name('students')->missing(fn() => redirect()->route('teacher.dashboard'));
    //     Route::get('/profile', \App\Livewire\Teacher\Profile::class)->name('profile')->missing(fn() => redirect()->route('teacher.dashboard'));
    // });

    // Rotas para responsáveis
    // Route::middleware('role:parent')->prefix('parent')->name('parent.')->group(function() {
    //     Route::get('/children', \App\Livewire\Parent\Children::class)->name('children')->missing(fn() => redirect()->route('parent.dashboard'));
    //     Route::get('/progress', \App\Livewire\Parent\Progress::class)->name('progress')->missing(fn() => redirect()->route('parent.dashboard'));
    //     Route::get('/messages', \App\Livewire\Parent\Messages::class)->name('messages')->missing(fn() => redirect()->route('parent.dashboard'));
    //     Route::get('/payments', \App\Livewire\Parent\Payments::class)->name('payments')->missing(fn() => redirect()->route('parent.dashboard'));
    //     Route::get('/profile', \App\Livewire\Parent\Profile::class)->name('profile')->missing(fn() => redirect()->route('parent.dashboard'));
    // });

    // Rotas para secretaria
    // Route::middleware('role:secretary,admin')->prefix('secretary')->name('secretary.')->group(function() {
    //     Route::get('/registrations', \App\Livewire\Secretary\Registrations::class)->name('registrations')->missing(fn() => redirect()->route('secretary.dashboard'));
    //     Route::get('/students', \App\Livewire\Secretary\Students::class)->name('students')->missing(fn() => redirect()->route('secretary.dashboard'));
    //     Route::get('/teachers', \App\Livewire\Secretary\Teachers::class)->name('teachers')->missing(fn() => redirect()->route('secretary.dashboard'));
    //     Route::get('/courses', \App\Livewire\Secretary\Courses::class)->name('courses')->missing(fn() => redirect()->route('secretary.dashboard'));
    //     Route::get('/subjects', \App\Livewire\Secretary\Subjects::class)->name('subjects')->missing(fn() => redirect()->route('secretary.dashboard'));
    //     Route::get('/reports', \App\Livewire\Secretary\Reports::class)->name('reports')->missing(fn() => redirect()->route('secretary.dashboard'));
    //     Route::get('/profile', \App\Livewire\Secretary\Profile::class)->name('profile')->missing(fn() => redirect()->route('secretary.dashboard'));
    // });

    // Rotas para administrador
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', Users::class)->name('users');
        Route::get('/settings', Settings::class)->name('settings');
        Route::get('/logs', Logs::class)->name('logs');
        Route::get('/backup', Backup::class)->name('backup');
        Route::get('/system', System::class)->name('system');
    });

    // Rotas comuns (acessíveis por todos os usuários autenticados)
    // Route::prefix('common')->name('common.')->group(function() {
    //     Route::get('/messages', \App\Livewire\Chat\Index::class)->name('messages.index')->missing(fn() => redirect()->route('dashboard'));
    //     Route::get('/messages/{user}', \App\Livewire\Chat\Conversation::class)->name('messages.conversation')->middleware('can:view,user');
    //     Route::get('/blog', \App\Livewire\Blog\Index::class)->name('blog.index')->missing(fn() => redirect()->route('dashboard'));
    //     Route::get('/blog/{post}', \App\Livewire\Blog\Show::class)->name('blog.show')->middleware('can:view,post');
    //     Route::get('/events', \App\Livewire\Events\Index::class)->name('events.index')->missing(fn() => redirect()->route('dashboard'));
    //     Route::get('/calendar', \App\Livewire\Calendar\Index::class)->name('calendar')->missing(fn() => redirect()->route('dashboard'));
    //     Route::get('/profile', \App\Livewire\Profile\Index::class)->name('profile')->missing(fn() => redirect()->route('dashboard'));
    //     Route::get('/help', \App\Livewire\Help\Index::class)->name('help')->missing(fn() => redirect()->route('dashboard'));
    //     Route::get('/contact', \App\Livewire\Contact\Index::class)->name('contact')->missing(fn() => redirect()->route('dashboard'));
    // });
});

// Rota fallback para 404
Route::fallback(function () {
    return redirect()->route('dashboard')->with('error', 'Página não encontrada.');
});
