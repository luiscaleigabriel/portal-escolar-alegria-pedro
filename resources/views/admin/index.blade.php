@extends('layouts.app')
@section('title', 'Administrativo - Portal Alegria')
@section('cssjs')
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
@endsection
@section('content')
    <div class="admin-wrapper">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Conteúdo Principal -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="toggle-sidebar" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="breadcrumb">
                        <span class="text-muted">Admin</span>
                        <span class="mx-2">/</span>
                        <span class="fw-medium">Dashboard</span>
                    </div>
                </div>

                <div class="header-right">
                    <button class="btn btn-sm btn-outline-secondary" id="fullscreenBtn">
                        <i class="fas fa-expand"></i>
                    </button>

                    <div class="notification-indicator">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge"></span>
                        </button>
                    </div>

                    <div class="dropdown">
                        <div class="user-profile dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="user-info">
                                <h6>{{ auth()->user()->name }}</h6>
                                <small>Administrador</small>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user me-2"></i>
                                Perfil
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i>
                                Configurações
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Sair
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Conteúdo -->
            <div class="admin-content">
                @if (isset($stats))
                    <!-- Cabeçalho da Página -->
                    <div class="page-header">
                        <h1 class="page-title">Painel Administrativo</h1>
                        <p class="page-subtitle">
                            <i class="fas fa-clock me-1"></i>
                            Última atualização: {{ now()->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <!-- Estatísticas -->
                    <div class="stats-grid">
                        <!-- Total de Usuários -->
                        <div class="stat-card animate-card" style="animation-delay: 0.1s">
                            <div class="stat-icon ip-gradient-primary">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div class="stat-info">
                                <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                                <p class="stat-title">Total de Usuários</p>
                                <div class="stat-trend trend-up">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>12% este mês</span>
                                </div>
                            </div>
                        </div>

                        <!-- Aguardando Aprovação -->
                        <div class="stat-card animate-card" style="animation-delay: 0.2s">
                            <div class="stat-icon ip-gradient-gold">
                                <i class="fas fa-clock text-dark"></i>
                            </div>
                            <div class="stat-info">
                                <h3>{{ $stats['pending_users'] ?? 0 }}</h3>
                                <p class="stat-title">Pendentes de Aprovação</p>
                                <div class="stat-trend trend-up">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>{{ $stats['pending_users'] ?? 0 }} novos</span>
                                </div>
                            </div>
                        </div>

                        <!-- Total de Alunos -->
                        <div class="stat-card animate-card" style="animation-delay: 0.3s">
                            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="stat-info">
                                <h3>{{ $stats['total_students'] ?? 0 }}</h3>
                                <p class="stat-title">Total de Alunos</p>
                                <div class="stat-trend trend-up">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>8% este mês</span>
                                </div>
                            </div>
                        </div>

                        <!-- Total de Professores -->
                        <div class="stat-card animate-card" style="animation-delay: 0.4s">
                            <div class="stat-icon"
                                style="background: rgba(96, 165, 250, 0.1); color: var(--ip-accent-blue);">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="stat-info">
                                <h3>{{ $stats['total_teachers'] ?? 0 }}</h3>
                                <p class="stat-title">Total de Professores</p>
                                <div class="stat-trend trend-up">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>5% este mês</span>
                                </div>
                            </div>
                        </div>

                        <!-- Total de Turmas -->
                        <div class="stat-card animate-card" style="animation-delay: 0.5s">
                            <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                                <i class="fas fa-school"></i>
                            </div>
                            <div class="stat-info">
                                <h3>{{ $stats['total_turmas'] ?? 0 }}</h3>
                                <p class="stat-title">Total de Turmas</p>
                                <div class="stat-trend trend-up">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>3% este mês</span>
                                </div>
                            </div>
                        </div>

                        <!-- Aprovações -->
                        <div class="stat-card animate-card" style="animation-delay: 0.6s">
                            <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-info">
                                <h3>{{ $stats['approved'] ?? 0 }}</h3>
                                <p class="stat-title">Aprovados</p>
                                <div class="stat-trend trend-up">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>18% este mês</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos e Tabelas -->
                    <div class="dashboard-grid">
                        <!-- Usuários Recentes -->
                        <div class="dashboard-card animate-card">
                            <div class="card-header">
                                <h3 class="card-title">Usuários Recentes</h3>
                                <div class="card-actions">
                                    <a href="{{ route('admin.users.index') }}">
                                        <button class="btn btn-sm btn-ip-outline">
                                            Ver Todos
                                        </button>
                                    </a>
                                </div>
                            </div>

                            @if (isset($stats['recent_registrations']) && $stats['recent_registrations']->count() > 0)
                                <div class="table-responsive">
                                    <table class="recent-users-table">
                                        <thead>
                                            <tr>
                                                <th>Usuário</th>
                                                <th>Email</th>
                                                <th>Perfil</th>
                                                <th>Status</th>
                                                <th>Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($stats['recent_registrations'] as $user)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="user-avatar-sm">
                                                                {{ substr($user->name, 0, 1) }}
                                                            </div>
                                                            <span>{{ $user->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        @foreach ($user->roles as $role)
                                                            <span
                                                                class="badge bg-secondary me-1">{{ $role->name }}</span>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @if ($user->is_approved)
                                                            <span class="status-badge status-approved">Aprovado</span>
                                                        @else
                                                            <span class="status-badge status-pending">Pendente</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Nenhum usuário recente encontrado.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Distribuição por Perfil -->
                        <div class="dashboard-card animate-card">
                            <div class="card-header">
                                <h3 class="card-title">Distribuição por Perfil</h3>
                            </div>

                            <div class="role-distribution">
                                @foreach ($stats['by_role'] ?? [] as $role => $count)
                                    @if ($role && $count > 0)
                                        <div class="role-item">
                                            <div class="role-info">
                                                <div class="role-icon ip-gradient-primary">
                                                    @switch($role)
                                                        @case('student')
                                                            <i class="fas fa-user-graduate"></i>
                                                        @break

                                                        @case('teacher')
                                                            <i class="fas fa-chalkboard-teacher"></i>
                                                        @break

                                                        @case('guardian')
                                                            <i class="fas fa-user-friends"></i>
                                                        @break

                                                        @case('admin')
                                                            <i class="fas fa-user-shield"></i>
                                                        @break

                                                        @case('director')
                                                            <i class="fas fa-user-tie"></i>
                                                        @break

                                                        @default
                                                            <i class="fas fa-user"></i>
                                                    @endswitch
                                                </div>
                                                <span class="role-name">
                                                    {{ ucfirst($role) }}
                                                </span>
                                            </div>
                                            <span class="role-count">{{ $count }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Status dos Usuários -->
                            <div class="mt-4">
                                <h6 class="fw-semibold mb-3">Status dos Usuários</h6>
                                <div class="d-flex justify-content-between">
                                    <div class="text-center">
                                        <div class="display-6 text-success fw-bold">{{ $stats['approved'] ?? 0 }}</div>
                                        <small class="text-muted">Aprovados</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="display-6 text-warning fw-bold">{{ $stats['pending_users'] ?? 0 }}
                                        </div>
                                        <small class="text-muted">Pendentes</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="display-6 text-danger fw-bold">{{ $stats['rejected'] ?? 0 }}</div>
                                        <small class="text-muted">Rejeitados</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Ações Rápidas -->
                            <div class="mt-4">
                                <h6 class="fw-semibold mb-3">Ações Rápidas</h6>
                                <div class="quick-actions">
                                    <a href="#" class="quick-action-btn">
                                        <i class="fas fa-user-plus action-icon"></i>
                                        <span class="action-text">Novo Usuário</span>
                                    </a>
                                    <a href="#" class="quick-action-btn">
                                        <i class="fas fa-check-circle action-icon"></i>
                                        <span class="action-text">Aprovar Pendentes</span>
                                    </a>
                                    <a href="#" class="quick-action-btn">
                                        <i class="fas fa-chart-bar action-icon"></i>
                                        <span class="action-text">Relatórios</span>
                                    </a>
                                    <a href="#" class="quick-action-btn">
                                        <i class="fas fa-cog action-icon"></i>
                                        <span class="action-text">Configurações</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Fallback se não houver dados -->
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h3 class="text-warning mb-3">Dados não disponíveis</h3>
                        <p class="text-muted">Os dados estatísticos não puderam ser carregados. Por favor, verifique a
                            conexão com o banco de dados.</p>
                        <button class="btn btn-ip-primary mt-2" onclick="window.location.reload()">
                            <i class="fas fa-sync me-2"></i>
                            Recarregar
                        </button>
                    </div>
                @endif
            </div>
        </main>
    </div>
@endsection
