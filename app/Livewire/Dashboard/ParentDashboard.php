<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use App\Models\Grade;
use Livewire\Component;

class ParentDashboard extends Component
{
    public $stats = [];
    public $children = [];
    public $recentGrades = [];
    public $upcomingEvents = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadChildren();
        $this->loadRecentGrades();
        $this->loadUpcomingEvents();
    }

    private function loadStats()
    {
        $parent = auth()->user();

        // Total de educandos
        $totalChildren = $parent->children()->count();

        // Média geral dos educandos
        $averageGrades = Grade::whereIn('student_id', $parent->children()->pluck('id'))
            ->avg('value') ?? 0;

        // Notificações pendentes
        $unreadMessages = Message::where('receiver_id', $parent->id)
            ->where('is_read', false)
            ->count();

        // Tarefas pendentes dos educandos
        $pendingTasks = 0; // Seria calculado com base nas tarefas dos filhos

        $this->stats = [
            [
                'title' => 'Educandos',
                'value' => $totalChildren,
                'icon' => 'fas fa-child',
                'color' => 'primary',
                'change' => null
            ],
            [
                'title' => 'Média Geral',
                'value' => number_format($averageGrades, 1),
                'icon' => 'fas fa-chart-line',
                'color' => 'success',
                'change' => '+0.3'
            ],
            [
                'title' => 'Mensagens',
                'value' => $unreadMessages,
                'icon' => 'fas fa-envelope',
                'color' => 'warning',
                'change' => null
            ],
            [
                'title' => 'Reuniões',
                'value' => '2',
                'icon' => 'fas fa-calendar-check',
                'color' => 'info',
                'change' => null
            ]
        ];
    }

    private function loadChildren()
    {
        $this->children = auth()->user()->children()
            ->with(['coursesAsStudent' => function($query) {
                $query->wherePivot('status', 'active');
            }])
            ->get()
            ->map(function($child) {
                // Calcular média do aluno
                $average = Grade::where('student_id', $child->id)
                    ->avg('value') ?? 0;

                // Últimas notas
                $latestGrades = Grade::where('student_id', $child->id)
                    ->with('subject')
                    ->orderBy('date', 'desc')
                    ->limit(2)
                    ->get()
                    ->map(function($grade) {
                        return [
                            'subject' => $grade->subject->name,
                            'value' => $grade->value
                        ];
                    })->toArray();

                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'photo' => $child->full_photo_url,
                    'course' => $child->coursesAsStudent->first()->name ?? 'Não matriculado',
                    'average' => number_format($average, 1),
                    'latest_grades' => $latestGrades,
                    'status' => $average >= 14 ? 'excellent' : ($average >= 10 ? 'good' : 'attention')
                ];
            })
            ->toArray();
    }

    private function loadRecentGrades()
    {
        $childrenIds = auth()->user()->children()->pluck('id');

        $this->recentGrades = Grade::whereIn('student_id', $childrenIds)
            ->with(['student', 'subject', 'teacher'])
            ->orderBy('date', 'desc')
            ->limit(6)
            ->get()
            ->map(function($grade) {
                return [
                    'student' => $grade->student->name,
                    'subject' => $grade->subject->name,
                    'teacher' => $grade->teacher->name,
                    'value' => $grade->value,
                    'date' => $grade->date->format('d/m/Y'),
                    'type' => $grade->type
                ];
            })
            ->toArray();
    }

    private function loadUpcomingEvents()
    {
        $this->upcomingEvents = [
            [
                'title' => 'Reunião de Pais - 10º Ano',
                'date' => '10/02/2026',
                'time' => '15:00',
                'type' => 'meeting'
            ],
            [
                'title' => 'Entrega de Boletins',
                'date' => '15/02/2026',
                'time' => 'Todo o dia',
                'type' => 'academic'
            ],
            [
                'title' => 'Feira Tecnológica',
                'date' => '25/02/2026',
                'time' => '09:00 - 17:00',
                'type' => 'event'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.parent-dashboard')
            ->layout('layouts.app', [
                'pageTitle' => 'Dashboard do Responsável',
                'pageSubtitle' => 'Acompanhamento dos Educandos'
            ]);
    }
}
