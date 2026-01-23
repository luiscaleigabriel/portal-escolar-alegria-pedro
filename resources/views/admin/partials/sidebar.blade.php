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
                <a href="{{ route('dashboard') }}" class="nav-link active">
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
                <a href="{{ route('admin.users.index') }}" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <span>Usuários</span>
                    <span class="nav-badge">{{ $stats['pending_users'] ?? 0 }}</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.students.index') }}" class="nav-link">
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
