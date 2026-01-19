<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Turma;
use App\Models\Grade;
use App\Models\Absence;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('director')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('teacher')) {
            return $this->teacherDashboard($user);
        } elseif ($user->hasRole('student')) {
            return $this->studentDashboard($user);
        } elseif ($user->hasRole('guardian')) {
            return $this->guardianDashboard($user);
        }

        // Fallback - redireciona para home
        return redirect()->route('home');
    }

    private function adminDashboard()
    {
        $data = [
            'students' => Student::count(),
            'teachers' => Teacher::count(),
            'turmas' => Turma::count(),
            'recent_students' => Student::with('user')->latest()->take(5)->get(),
            'recent_teachers' => Teacher::with('user')->latest()->take(5)->get(),
        ];

        return view('dashboard.admin', $data);
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
