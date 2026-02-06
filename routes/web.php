<?php

use App\Livewire\Admin\{Backup, Logs, Settings, System, Users};
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard;
use App\Livewire\Dashboard\AdminDashboard;
use App\Livewire\Dashboard\Index;
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

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        session()->flush();
        return redirect('/login')->with('message', 'Sessão terminada com sucesso.');
    })->name('logout');


    // Dashboard principal
    Route::get('/dashboard', Index::class)->name('dashboard');

    // Dashboards específicos
    Route::get('/student/dashboard', StudentDashboard::class)->name('student.dashboard');
    Route::get('/teacher/dashboard', TeacherDashboard::class)->name('teacher.dashboard');
    Route::get('/parent/dashboard', ParentDashboard::class)->name('parent.dashboard');
    Route::get('/secretary/dashboard', SecretaryDashboard::class)->name('secretary.dashboard');
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');


    // Rotas para estudantes
    // Route::middleware('role:student')->prefix('student')->name('student.')->group(function() {
    //     Route::get('/grades', \App\Livewire\Student\Grades::class)->name('grades');
    //     Route::get('/subjects', \App\Livewire\Student\Subjects::class)->name('subjects');
    //     Route::get('/tasks', \App\Livewire\Student\Tasks::class)->name('tasks');
    //     Route::get('/profile', \App\Livewire\Student\Profile::class)->name('profile');
    //     Route::get('/timetable', \App\Livewire\Student\Timetable::class)->name('timetable');
    //     Route::get('/attendances', \App\Livewire\Student\Attendances::class)->name('attendances');
    // });

    // Rotas para professores
    // Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function() {
    //     Route::get('/classes', \App\Livewire\Teacher\Classes::class)->name('classes');
    //     Route::get('/grades', \App\Livewire\Teacher\Grades::class)->name('grades');
    //     Route::get('/tasks', \App\Livewire\Teacher\Tasks::class)->name('tasks');
    //     Route::get('/attendance', \App\Livewire\Teacher\Attendance::class)->name('attendance');
    //     Route::get('/students', \App\Livewire\Teacher\Students::class)->name('students');
    //     Route::get('/profile', \App\Livewire\Teacher\Profile::class)->name('profile');
    // });

    // Rotas para responsáveis
    // Route::middleware('role:parent')->prefix('parent')->name('parent.')->group(function() {
    //     Route::get('/children', \App\Livewire\Parent\Children::class)->name('children');
    //     Route::get('/progress', \App\Livewire\Parent\Progress::class)->name('progress');
    //     Route::get('/messages', \App\Livewire\Parent\Messages::class)->name('messages');
    //     Route::get('/payments', \App\Livewire\Parent\Payments::class)->name('payments');
    //     Route::get('/profile', \App\Livewire\Parent\Profile::class)->name('profile');
    // });

    // Rotas para secretaria
    // Route::middleware('role:secretary,admin')->prefix('secretary')->name('secretary.')->group(function() {
    //     Route::get('/registrations', \App\Livewire\Secretary\Registrations::class)->name('registrations');
    //     Route::get('/students', \App\Livewire\Secretary\Students::class)->name('students');
    //     Route::get('/teachers', \App\Livewire\Secretary\Teachers::class)->name('teachers');
    //     Route::get('/courses', \App\Livewire\Secretary\Courses::class)->name('courses');
    //     Route::get('/subjects', \App\Livewire\Secretary\Subjects::class)->name('subjects');
    //     Route::get('/reports', \App\Livewire\Secretary\Reports::class)->name('reports');
    //     Route::get('/profile', \App\Livewire\Secretary\Profile::class)->name('profile');
    // });

    // Rotas para administrador
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', Users::class)->name('users');
        Route::get('/settings', Settings::class)->name('settings');
        Route::get('/logs', Logs::class)->name('logs');
        Route::get('/backup', Backup::class)->name('backup');
        Route::get('/system', System::class)->name('system');
    });

    // Rotas comuns (acessíveis por todos)
    // Route::prefix('common')->name('common.')->group(function() {
    //     Route::get('/messages', \App\Livewire\Chat\Index::class)->name('messages.index');
    //     Route::get('/messages/{user}', \App\Livewire\Chat\Conversation::class)->name('messages.conversation');
    //     Route::get('/blog', \App\Livewire\Blog\Index::class)->name('blog.index');
    //     Route::get('/blog/{post}', \App\Livewire\Blog\Show::class)->name('blog.show');
    //     Route::get('/events', \App\Livewire\Events\Index::class)->name('events.index');
    //     Route::get('/calendar', \App\Livewire\Calendar\Index::class)->name('calendar');
    //     Route::get('/profile', \App\Livewire\Profile\Index::class)->name('profile');
    //     Route::get('/settings', \App\Livewire\Settings\Index::class)->name('settings');
    //     Route::get('/help', \App\Livewire\Help\Index::class)->name('help');
    //     Route::get('/contact', \App\Livewire\Contact\Index::class)->name('contact');
    // });
});
