<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Portal Alegria</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Seus estilos -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fb;
            overflow-x: hidden;
        }

        /* Layout Principal */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--ip-dark-bg) 0%, #1a2238 100%);
            color: white;
            position: fixed;
            height: 100vh;
            z-index: 100;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-container {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--ip-primary-blue) 0%, var(--ip-secondary-blue) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .sidebar-brand h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: white;
        }

        .sidebar-brand small {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
            height: calc(100vh - 80px);
            overflow-y: auto;
        }

        .nav-group {
            margin-bottom: 1.5rem;
        }

        .nav-group-title {
            padding: 0 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 0.75rem;
            letter-spacing: 1px;
        }

        .nav-item {
            padding: 0 0.75rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--ip-primary-blue) 0%, var(--ip-secondary-blue) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--ip-highlight-gold);
            color: #1F2937;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Conteúdo Principal */
        .admin-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Header */
        .admin-header {
            height: var(--header-height);
            background: white;
            box-shadow: var(--card-shadow);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .toggle-sidebar:hover {
            background: #f3f4f6;
            color: var(--ip-primary-blue);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            background: #f3f4f6;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--ip-primary-blue) 0%, var(--ip-secondary-blue) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .user-info h6 {
            margin: 0;
            font-weight: 600;
            color: #1f2937;
        }

        .user-info small {
            color: #6b7280;
            font-size: 0.75rem;
        }

        /* Conteúdo */
        .admin-content {
            padding: 1.5rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--ip-primary-blue) 0%, var(--ip-secondary-blue) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 0.9375rem;
        }

        /* Cards de Estatísticas */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: 1px solid rgba(229, 231, 235, 0.5);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-info h3 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .stat-title {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .trend-up {
            color: #10b981;
        }

        .trend-down {
            color: #ef4444;
        }

        /* Gráficos e Tabelas */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }

        .dashboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            border: 1px solid rgba(229, 231, 235, 0.5);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Tabela de Usuários Recentes */
        .recent-users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .recent-users-table thead th {
            background: #f9fafb;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .recent-users-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            color: #4b5563;
        }

        .recent-users-table tbody tr:hover {
            background: #f9fafb;
        }

        .user-avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .status-approved {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .status-rejected {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Distribuição por Perfil */
        .role-distribution {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .role-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: #f9fafb;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .role-item:hover {
            background: #f3f4f6;
        }

        .role-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .role-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.875rem;
        }

        .role-name {
            font-weight: 500;
            color: #1f2937;
        }

        .role-count {
            font-weight: 600;
            color: var(--ip-primary-blue);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .quick-action-btn:hover {
            border-color: var(--ip-accent-blue);
            color: var(--ip-primary-blue);
            transform: translateY(-3px);
        }

        .action-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .action-text {
            font-size: 0.75rem;
            font-weight: 500;
            text-align: center;
        }

        /* Responsividade */
        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.active {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .admin-content {
                padding: 1rem;
            }

            .user-info {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        /* Animações */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-card {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Scrollbar Personalizada */
        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Indicador de Notificações */
        .notification-indicator {
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="sidebar-brand">
                    <h2>Portal Alegria</h2>
                    <small>Painel Administrativo</small>
                </div>
            </div>

            <nav class="sidebar-menu">
                <div class="nav-group">
                    <div class="nav-group-title">Principal</div>
                    <div class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="fas fa-tachometer-alt nav-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-chart-line nav-icon"></i>
                            <span>Análises</span>
                        </a>
                    </div>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Gestão</div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-users nav-icon"></i>
                            <span>Usuários</span>
                            <span class="nav-badge">{{ $stats['pending_users'] ?? 0 }}</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user-graduate nav-icon"></i>
                            <span>Alunos</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-chalkboard-teacher nav-icon"></i>
                            <span>Professores</span>
                        </a>
                    </div>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Acadêmico</div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-school nav-icon"></i>
                            <span>Turmas</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-book nav-icon"></i>
                            <span>Disciplinas</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-clipboard-list nav-icon"></i>
                            <span>Notas</span>
                        </a>
                    </div>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Configurações</div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-cog nav-icon"></i>
                            <span>Sistema</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user-shield nav-icon"></i>
                            <span>Permissões</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

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
                @if(isset($stats))
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
                            <div class="stat-icon" style="background: rgba(96, 165, 250, 0.1); color: var(--ip-accent-blue);">
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
                                    <button class="btn btn-sm btn-ip-outline">
                                        Ver Todos
                                    </button>
                                </div>
                            </div>

                            @if(isset($stats['recent_registrations']) && $stats['recent_registrations']->count() > 0)
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
                                            @foreach($stats['recent_registrations'] as $user)
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
                                                        @foreach($user->roles as $role)
                                                            <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @if($user->is_approved)
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
                                @foreach($stats['by_role'] ?? [] as $role => $count)
                                    @if($role && $count > 0)
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
                                        <div class="display-6 text-warning fw-bold">{{ $stats['pending_users'] ?? 0 }}</div>
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
                        <p class="text-muted">Os dados estatísticos não puderam ser carregados. Por favor, verifique a conexão com o banco de dados.</p>
                        <button class="btn btn-ip-primary mt-2" onclick="window.location.reload()">
                            <i class="fas fa-sync me-2"></i>
                            Recarregar
                        </button>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('active');
        });

        // Fullscreen
        document.getElementById('fullscreenBtn').addEventListener('click', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log(`Error attempting to enable fullscreen: ${err.message}`);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        });

        // Animações de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.animate-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });

        // Auto-refresh pendentes
        setInterval(() => {
            const pendingBadge = document.querySelector('.nav-badge');
            if (pendingBadge) {
                fetch('/api/admin/pending-count')
                    .then(response => response.json())
                    .then(data => {
                        if (data.count > 0) {
                            pendingBadge.textContent = data.count;
                            pendingBadge.style.display = 'flex';
                        } else {
                            pendingBadge.style.display = 'none';
                        }
                    });
            }
        }, 30000); // A cada 30 segundos
    </script>
</body>
</html>
