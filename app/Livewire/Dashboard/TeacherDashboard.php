<?php

namespace App\Livewire\Dashboard;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Task;
use App\Models\Message;
use Livewire\Component;

class TeacherDashboard extends Component
{
    public $stats = [];
    public $upcomingClasses = [];
    public $recentGrades = [];
    public $pendingTasks = [];
    public $courses = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadUpcomingClasses();
        $this->loadRecentGrades();
        $this->loadPendingTasks();
        $this->loadCourses();
    }

    private function loadStats()
    {
        $teacher = auth()->user();

        // Total de turmas
        $totalClasses = $teacher->coursesAsTeacher()->count();

        // Total de alunos
        $totalStudents = $teacher->coursesAsTeacher()
            ->withCount('students')
            ->get()
            ->sum('students_count');

        // Média de notas atribuídas
        $averageGrades = Grade::where('teacher_id', $teacher->id)
            ->avg('value') ?? 0;

        // Tarefas pendentes
        $pendingTasksCount = Task::where('teacher_id', $teacher->id)
            ->where('status', 'pending')
            ->count();

        $this->stats = [
            [
                'title' => 'Turmas Ativas',
                'value' => $totalClasses,
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => 'primary',
                'change' => null
            ],
            [
                'title' => 'Total de Alunos',
                'value' => $totalStudents,
                'icon' => 'fas fa-users',
                'color' => 'success',
                'change' => null
            ],
            [
                'title' => 'Média de Notas',
                'value' => number_format($averageGrades, 1),
                'icon' => 'fas fa-chart-line',
                'color' => 'warning',
                'change' => null
            ],
            [
                'title' => 'Tarefas Pendentes',
                'value' => $pendingTasksCount,
                'icon' => 'fas fa-tasks',
                'color' => 'danger',
                'change' => null
            ]
        ];
    }

    private function loadUpcomingClasses()
    {
        // Simulando horário de aulas
        $this->upcomingClasses = [
            [
                'subject' => 'Matemática',
                'course' => '10º A',
                'time' => '08:00 - 09:30',
                'room' => 'Sala 201',
                'status' => 'soon'
            ],
            [
                'subject' => 'Física',
                'course' => '11º B',
                'time' => '10:00 - 11:30',
                'room' => 'Laboratório 3',
                'status' => 'later'
            ],
            [
                'subject' => 'Matemática',
                'course' => '9º C',
                'time' => '14:00 - 15:30',
                'room' => 'Sala 105',
                'status' => 'later'
            ]
        ];
    }

    private function loadRecentGrades()
    {
        $this->recentGrades = Grade::where('teacher_id', auth()->id())
            ->with(['student', 'subject', 'course'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($grade) {
                return [
                    'student' => $grade->student->name,
                    'subject' => $grade->subject->name,
                    'course' => $grade->course->name,
                    'value' => $grade->value,
                    'type' => $grade->type,
                    'date' => $grade->date->format('d/m/Y')
                ];
            })
            ->toArray();
    }

    private function loadPendingTasks()
    {
        $this->pendingTasks = Task::where('teacher_id', auth()->id())
            ->with(['subject', 'course'])
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->limit(5)
            ->get()
            ->map(function($task) {
                $daysLeft = now()->diffInDays($task->due_date, false);

                return [
                    'title' => $task->title,
                    'subject' => $task->subject->name,
                    'course' => $task->course->name,
                    'due_date' => $task->due_date->format('d/m/Y'),
                    'days_left' => $daysLeft >= 0 ? $daysLeft . ' dias' : 'Atrasada',
                    'status' => $daysLeft < 0 ? 'danger' : ($daysLeft <= 2 ? 'warning' : 'info')
                ];
            })
            ->toArray();
    }

    private function loadCourses()
    {
        $this->courses = auth()->user()->coursesAsTeacher()
            ->with(['subjects', 'students'])
            ->get()
            ->map(function($course) {
                return [
                    'name' => $course->name,
                    'level' => $course->level,
                    'students_count' => $course->students->count(),
                    'subjects' => $course->subjects->pluck('name')->toArray()
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.teacher-dashboard')
            ->layout('layouts.app', [
                'pageTitle' => 'Dashboard do Professor',
                'pageSubtitle' => 'Bem-vindo, Professor ' . auth()->user()->name
            ]);
    }
}
