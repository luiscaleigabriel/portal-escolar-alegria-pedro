<header class="admin-header">
    <div class="header-left">
        <button class="toggle-sidebar" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="breadcrumb">
            <span class="text-muted">Admin</span>
            <span class="separator mx-2">/</span>
            <span class="current">Dashboard</span>
        </div>
    </div>

    <div class="header-right">
        <button class="btn btn-outline-secondary btn-sm" id="fullscreenBtn" title="Tela Cheia (F11)">
            <i class="fas fa-expand"></i>
        </button>

        <div class="notification-indicator">
            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown" title="Notificações">
                <i class="fas fa-bell"></i>
                @if(isset($stats['pending_users']) && $stats['pending_users'] > 0)
                <span class="notification-badge"></span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <h6 class="dropdown-header">Notificações</h6>
                @if(isset($stats['pending_users']) && $stats['pending_users'] > 0)
                <a class="dropdown-item" href="{{ route('admin.users.pending') }}">
                    <i class="fas fa-users text-warning me-2"></i>
                    <span>{{ $stats['pending_users'] }} usuários pendentes</span>
                </a>
                @endif
                <div class="dropdown-divider"></div>
                <a class="dropdown-item small text-center" href="#">
                    Ver todas
                </a>
            </div>
        </div>

        <div class="dropdown">
            <div class="user-profile dropdown-toggle" data-bs-toggle="dropdown">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-info">
                    <h6>{{ auth()->user()->name }}</h6>
                    <small>Administrador</small>
                </div>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="">
                    <i class="fas fa-user me-2"></i>
                    Perfil
                </a>
                <a class="dropdown-item" href="">
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
