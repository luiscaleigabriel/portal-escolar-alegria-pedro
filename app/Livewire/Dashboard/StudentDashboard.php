<?php

namespace App\Livewire\Dashboard;

use App\Models\Course;
use App\Models\Grade;
use App\Models\Task;
use App\Models\Message;
use Livewire\Component;

class StudentDashboard extends Component
{
    public $stats = [];
    public $recentGrades = [];
    public $pendingTasks = [];
    public $courses = [];
    public $latestNews = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentGrades();
        $this->loadPendingTasks();
        $this->loadCourses();
        $this->loadLatestNews();
    }

    private function loadStats()
    {
        $user = auth()->user();

        // Média global
        $average = Grade::where('student_id', $user->id)
            ->avg('value') ?? 0;

        // Presença (simulado - precisaria de tabela de presenças)
        $attendance = 92; // Em porcentagem

        // Tarefas pendentes
        $pendingTasksCount = Task::whereHas('course', function($query) use ($user) {
                $query->whereHas('students', function($q) use ($user) {
                    // $q->where('user_id', $user->id);
                });
            })
            ->where('status', 'pending')
            ->where('due_date', '>=', now())
            ->count();

        // Mensagens não lidas
        $unreadMessages = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        $this->stats = [
            [
                'title' => 'Média Global',
                'value' => number_format($average, 1),
                'icon' => 'fas fa-chart-line',
                'color' => 'primary',
                'change' => '+0.5',
                'change_type' => 'positive'
            ],
            [
                'title' => 'Presença',
                'value' => $attendance . '%',
                'icon' => 'fas fa-user-check',
                'color' => 'success',
                'change' => '+2%',
                'change_type' => 'positive'
            ],
            [
                'title' => 'Tarefas Pendentes',
                'value' => $pendingTasksCount,
                'icon' => 'fas fa-tasks',
                'color' => 'warning',
                'change' => null,
                'change_type' => null
            ],
            [
                'title' => 'Mensagens',
                'value' => $unreadMessages,
                'icon' => 'fas fa-envelope',
                'color' => 'info',
                'change' => null,
                'change_type' => null
            ]
        ];
    }

    private function loadRecentGrades()
    {
        $this->recentGrades = Grade::where('student_id', auth()->id())
            ->with(['subject', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($grade) {
                return [
                    'subject' => $grade->subject->name,
                    'teacher' => $grade->teacher->name,
                    'value' => $grade->value,
                    'type' => $this->getGradeType($grade->type),
                    'date' => $grade->date->format('d/m/Y'),
                    'trimester' => $grade->trimester . 'º Trimestre'
                ];
            })
            ->toArray();
    }

    private function getGradeType($type)
    {
        return match($type) {
            'test' => 'Teste',
            'exam' => 'Exame',
            'homework' => 'Trabalho',
            'participation' => 'Participação',
            'project' => 'Projecto',
            default => $type
        };
    }

    private function loadPendingTasks()
    {
        $this->pendingTasks = Task::whereHas('course', function($query) {
                $query->whereHas('students', function($q) {
                    // $q->where('user_id', auth()->id());
                });
            })
            ->with(['subject', 'teacher'])
            ->where('status', 'pending')
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->limit(5)
            ->get()
            ->map(function($task) {
                $daysLeft = now()->diffInDays($task->due_date, false);

                return [
                    'title' => $task->title,
                    'subject' => $task->subject->name,
                    'teacher' => $task->teacher->name,
                    'due_date' => $task->due_date->format('d/m/Y'),
                    'days_left' => $daysLeft >= 0 ? $daysLeft . ' dias' : 'Atrasada',
                    'status' => $daysLeft < 0 ? 'danger' : ($daysLeft <= 2 ? 'warning' : 'success')
                ];
            })
            ->toArray();
    }

    private function loadCourses()
    {
        $this->courses = auth()->user()->coursesAsStudent()
            ->with(['subjects' => function($query) {
                $query->with('teachers');
            }])
            ->wherePivot('status', 'active')
            ->get()
            ->map(function($course) {
                return [
                    'name' => $course->name,
                    'level' => $course->level,
                    'year' => $course->school_year,
                    'subjects' => $course->subjects->map(function($subject) {
                        return [
                            'name' => $subject->name,
                            'teacher' => $subject->teachers->first()->name ?? 'N/A'
                        ];
                    })->toArray()
                ];
            })
            ->toArray();
    }

    private function loadLatestNews()
    {
        // Aqui você carregaria as notícias do blog
        $this->latestNews = [
            [
                'title' => 'Feira Tecnológica 2026',
                'category' => 'Evento',
                'date' => '03/02/2026',
                'excerpt' => 'Participe da maior feira tecnológica da região'
            ],
            [
                'title' => 'Início das Provas do 1º Trimestre',
                'category' => 'Aviso',
                'date' => '01/02/2026',
                'excerpt' => 'Calendário de provas disponível'
            ],
            [
                'title' => 'Workshop de Programação',
                'category' => 'Oportunidade',
                'date' => '28/01/2026',
                'excerpt' => 'Inscrições abertas para workshop gratuito'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.student-dashboard')
            ->layout('layouts.app', [
                'pageTitle' => 'Dashboard do Aluno',
                'pageSubtitle' => 'Bem-vindo, ' . auth()->user()->name
            ]);
    }
}
