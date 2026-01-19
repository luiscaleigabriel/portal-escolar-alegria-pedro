<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Turma;
use App\Models\Grade;
use App\Models\Absence;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'approved']);
    }

    public function index()
    {
        $user = auth()->user();

        // Se for admin/director, mostrar dashboard admin
        if ($user->hasRole(['admin', 'director'])) {
            return $this->adminDashboard();
        }

        // Verificar se o usuário tem o perfil correspondente
        if ($user->hasRole('student') && !$user->student) {
            return view('dashboard.profile-incomplete', [
                'message' => 'Seu perfil de aluno ainda não está completo. Aguarde a configuração pela administração.'
            ]);
        }

        if ($user->hasRole('teacher') && !$user->teacher) {
            return view('dashboard.profile-incomplete', [
                'message' => 'Seu perfil de professor ainda não está completo. Aguarde a configuração pela administração.'
            ]);
        }

        if ($user->hasRole('guardian') && !$user->guardian) {
            return view('dashboard.profile-incomplete', [
                'message' => 'Seu perfil de responsável ainda não está completo. Aguarde a configuração pela administração.'
            ]);
        }

        // Mostrar dashboard baseado no role
        if ($user->hasRole('teacher')) {
            return $this->teacherDashboard($user);
        } elseif ($user->hasRole('student')) {
            return $this->studentDashboard($user);
        } elseif ($user->hasRole('guardian')) {
            return $this->guardianDashboard($user);
        }

        // Fallback
        return redirect()->route('home');
    }

    private function adminDashboard()
    {
        $stats = [
            'pending_users' => User::where('status', 'pending')->count(),
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_turmas' => Turma::count(),
            'recent_registrations' => User::latest()->take(5)->get(),
        ];

        return view('dashboard.admin', $stats);
    }

    private function teacherDashboard($user)
    {
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'Usuário não tem perfil de professor');
        }

        $data = [
            'teacher' => $teacher,
            'turmas' => $teacher->turmas()->count(),
            'subjects' => $teacher->subjects()->count(),
            'total_students' => Student::whereIn('turma_id', $teacher->turmas()->pluck('id'))->count(),
            'grades_to_launch' => 0, // Implementar lógica
            'absences_to_register' => 0, // Implementar lógica
        ];

        return view('dashboard.teacher', $data);
    }

    private function studentDashboard($user)
    {
        $student = $user->student;

        if (!$student) {
            abort(403, 'Usuário não tem perfil de aluno');
        }

        $data = [
            'student' => $student,
            'turma' => $student->turma,
            'grades' => $student->grades()->with('subject')->latest()->take(5)->get(),
            'absences' => $student->absences()->with('subject')->latest()->take(5)->get(),
            'average_grade' => $student->grades()->avg('value'),
            'total_absences' => $student->absences()->count(),
        ];

        return view('dashboard.student', $data);
    }

    private function guardianDashboard($user)
    {
        $guardian = $user->guardian;

        if (!$guardian) {
            abort(403, 'Usuário não tem perfil de responsável');
        }

        $students = $guardian->students()->with('user', 'turma')->get();

        $data = [
            'guardian' => $guardian,
            'students' => $students,
            'total_students' => $students->count(),
        ];

        return view('dashboard.guardian', $data);
    }
}
