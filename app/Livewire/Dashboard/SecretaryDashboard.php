<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use Livewire\Component;

class SecretaryDashboard extends Component
{
    public $stats = [];
    public $pendingRegistrations = [];
    public $recentActivities = [];
    public $upcomingEvents = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadPendingRegistrations();
        $this->loadRecentActivities();
        $this->loadUpcomingEvents();
    }

    private function loadStats()
    {
        // Inscrições pendentes
        $pendingRegistrations = User::where('is_approved', false)
            ->whereIn('role', ['student', 'teacher', 'parent'])
            ->count();

        // Total de alunos
        $totalStudents = User::where('role', 'student')
            ->where('is_approved', true)
            ->count();

        // Total de professores
        $totalTeachers = User::where('role', 'teacher')
            ->where('is_approved', true)
            ->count();

        // Total de turmas
        $totalCourses = Course::where('is_active', true)->count();

        $this->stats = [
            [
                'title' => 'Inscrições Pendentes',
                'value' => $pendingRegistrations,
                'icon' => 'fas fa-user-clock',
                'color' => 'warning',
                'route' => 'secretary.registrations'
            ],
            [
                'title' => 'Alunos Ativos',
                'value' => $totalStudents,
                'icon' => 'fas fa-graduation-cap',
                'color' => 'primary',
                'route' => 'secretary.students'
            ],
            [
                'title' => 'Professores',
                'value' => $totalTeachers,
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => 'success',
                'route' => 'secretary.teachers'
            ],
            [
                'title' => 'Turmas Ativas',
                'value' => $totalCourses,
                'icon' => 'fas fa-school',
                'color' => 'info',
                'route' => 'secretary.courses'
            ]
        ];
    }

    private function loadPendingRegistrations()
    {
        $this->pendingRegistrations = User::where('is_approved', false)
            ->whereIn('role', ['student', 'teacher', 'parent'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($user) {
                $roleNames = [
                    'student' => 'Aluno',
                    'teacher' => 'Professor',
                    'parent' => 'Responsável'
                ];

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $roleNames[$user->role] ?? $user->role,
                    'created_at' => $user->created_at->format('d/m/Y H:i'),
                    'days_pending' => $user->created_at->diffInDays(now())
                ];
            })
            ->toArray();
    }

    private function loadRecentActivities()
    {
        // Atividades recentes do sistema
        $this->recentActivities = [
            [
                'user' => 'Maria Silva',
                'action' => 'Nova inscrição de aluno',
                'time' => 'há 2 horas',
                'type' => 'registration'
            ],
            [
                'user' => 'Professor João',
                'action' => 'Lançou notas de Matemática',
                'time' => 'há 4 horas',
                'type' => 'grade'
            ],
            [
                'user' => 'Administrador',
                'action' => 'Publicou nova notícia',
                'time' => 'há 1 dia',
                'type' => 'blog'
            ],
            [
                'user' => 'Carlos Santos',
                'action' => 'Inscrição aprovada',
                'time' => 'há 1 dia',
                'type' => 'approval'
            ]
        ];
    }

    private function loadUpcomingEvents()
    {
        $this->upcomingEvents = [
            [
                'title' => 'Fim do 1º Trimestre',
                'date' => '15/03/2026',
                'priority' => 'high'
            ],
            [
                'title' => 'Reunião Pedagógica',
                'date' => '20/03/2026',
                'priority' => 'medium'
            ],
            [
                'title' => 'Entrega de Documentos',
                'date' => '31/03/2026',
                'priority' => 'low'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.secretary-dashboard')
            ->layout('layouts.app', [
                'pageTitle' => 'Dashboard da Secretaria',
                'pageSubtitle' => 'Gestão Acadêmica'
            ]);
    }
}
