<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Post;
use App\Models\Message;
use Livewire\Component;

class AdminDashboard extends Component
{
    public $stats = [];
    public $systemHealth = [];
    public $recentLogs = [];
    public $userActivity = [];
    public $activityChart = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadSystemHealth();
        $this->loadRecentLogs();
        $this->loadUserActivity();
        $this->loadActivityChart();
    }

    private function loadStats()
    {
        // Estatísticas gerais do sistema
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $todayLogins = User::whereDate('last_login_at', today())->count();
        $newUsersToday = User::whereDate('created_at', today())->count();

        $totalPosts = Post::count();
        $publishedPosts = Post::published()->count();
        $totalMessages = Message::count();
        $unreadMessages = Message::where('is_read', false)->count();

        $this->stats = [
            [
                'title' => 'Usuários Totais',
                'value' => $totalUsers,
                'icon' => 'fas fa-users',
                'color' => 'primary',
                'change' => $newUsersToday > 0 ? "+{$newUsersToday}" : null,
                'change_type' => $newUsersToday > 0 ? 'positive' : null
            ],
            [
                'title' => 'Usuários Ativos',
                'value' => $activeUsers,
                'icon' => 'fas fa-user-check',
                'color' => 'success',
                'change' => $todayLogins > 0 ? "+{$todayLogins}" : null,
                'change_type' => $todayLogins > 0 ? 'positive' : null
            ],
            [
                'title' => 'Publicações',
                'value' => $totalPosts,
                'icon' => 'fas fa-newspaper',
                'color' => 'info',
                'change' => $publishedPosts . ' publicadas',
                'change_type' => 'info'
            ],
            [
                'title' => 'Mensagens',
                'value' => $totalMessages,
                'icon' => 'fas fa-comments',
                'color' => 'warning',
                'change' => $unreadMessages . ' não lidas',
                'change_type' => $unreadMessages > 0 ? 'negative' : 'positive'
            ]
        ];
    }

    private function loadSystemHealth()
    {
        // Em produção, você obteria esses dados de APIs de monitoramento
        $this->systemHealth = [
            [
                'name' => 'Servidor Web',
                'status' => 'online',
                'uptime' => '99.9%',
                'color' => 'success'
            ],
            [
                'name' => 'Base de Dados',
                'status' => 'online',
                'uptime' => '99.8%',
                'color' => 'success'
            ],
            [
                'name' => 'Servidor de Email',
                'status' => 'online',
                'uptime' => '98.5%',
                'color' => 'warning'
            ],
            [
                'name' => 'Armazenamento',
                'status' => '65% usado',
                'uptime' => '35% livre',
                'color' => 'info'
            ]
        ];
    }

    private function loadRecentLogs()
    {
        // Aqui você buscaria os logs reais do Laravel
        // Por enquanto, dados simulados
        $this->recentLogs = [
            [
                'type' => 'login',
                'message' => 'Usuário admin logado',
                'time' => 'há 5 minutos',
                'ip' => '192.168.1.1'
            ],
            [
                'type' => 'error',
                'message' => 'Tentativa de login falhada',
                'time' => 'há 30 minutos',
                'ip' => '10.0.0.1'
            ],
            [
                'type' => 'update',
                'message' => 'Configurações atualizadas',
                'time' => 'há 2 horas',
                'ip' => '192.168.1.100'
            ],
            [
                'type' => 'backup',
                'message' => 'Backup automático realizado',
                'time' => 'há 6 horas',
                'ip' => 'localhost'
            ]
        ];
    }

    private function loadUserActivity()
    {
        // Atividade dos usuários
        $this->userActivity = User::select(['id', 'name', 'role', 'last_login_at', 'is_active', 'created_at'])
            ->orderBy('last_login_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $this->getRoleName($user->role),
                    'last_login' => $user->last_login_at
                        ? $user->last_login_at->diffForHumans()
                        : 'Nunca logou',
                    'status' => $user->is_active ? 'active' : 'inactive',
                    'created_at' => $user->created_at->format('d/m/Y')
                ];
            })
            ->toArray();
    }

    private function loadActivityChart()
    {
        // Últimos 7 dias de atividade
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates->push([
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d/m'),
                'logins' => User::whereDate('last_login_at', $date)->count(),
                'registrations' => User::whereDate('created_at', $date)->count()
            ]);
        }

        $this->activityChart = [
            'labels' => $dates->pluck('label')->toArray(),
            'datasets' => [
                [
                    'label' => 'Logins',
                    'data' => $dates->pluck('logins')->toArray(),
                    'borderColor' => '#003399',
                    'backgroundColor' => 'rgba(0, 51, 153, 0.1)',
                    'tension' => 0.4
                ],
                [
                    'label' => 'Novos Usuários',
                    'data' => $dates->pluck('registrations')->toArray(),
                    'borderColor' => '#28a745',
                    'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                    'tension' => 0.4
                ]
            ]
        ];
    }

    private function getRoleName($role)
    {
        $roles = [
            'admin' => 'Administrador',
            'secretary' => 'Secretaria',
            'teacher' => 'Professor',
            'student' => 'Aluno',
            'parent' => 'Responsável'
        ];

        return $roles[$role] ?? $role;
    }

    public function getLogIcon($type)
    {
        $icons = [
            'login' => 'sign-in-alt',
            'error' => 'exclamation-circle',
            'update' => 'sync-alt',
            'backup' => 'database',
            'warning' => 'exclamation-triangle',
            'info' => 'info-circle'
        ];

        return $icons[$type] ?? 'circle';
    }

    public function render()
    {
        return view('livewire.dashboard.admin-dashboard')
            ->layout('layouts.app', [
                'pageTitle' => 'Dashboard do Administrador',
                'pageSubtitle' => 'Monitoramento do Sistema'
            ]);
    }
}
