<!-- resources/views/layouts/partials/sidebar-menu.blade.php -->
@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';
    $currentRoute = request()->route()->getName();

    // Contadores para notificações
    $unreadMessagesCount = $user->unreadMessagesCount ?? 0;
    $pendingTasksCount = $user->pendingTasksCount ?? 0;
    $pendingRegistrationsCount = $user->pendingRegistrationsCount ?? 0;
@endphp

<!-- Dashboard -->
<div class="nav-section-title">Principal</div>
<a href="{{ route('dashboard') }}"
   class="nav-link {{ str_starts_with($currentRoute, 'dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i> Dashboard
</a>

@if($role === 'student')
    <!-- Menu do Aluno -->
    <div class="nav-section-title">Acadêmico</div>
    <a href="{{ route('student.subjects') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'student.subjects') ? 'active' : '' }}">
        <i class="fas fa-book-open"></i> Disciplinas
    </a>
    <a href="{{ route('student.grades') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'student.grades') ? 'active' : '' }}">
        <i class="fas fa-graduation-cap"></i> Notas/Boletim
    </a>
    <a href="{{ route('student.tasks') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'student.tasks') ? 'active' : '' }}">
        <i class="fas fa-tasks"></i> Tarefas
        @if($pendingTasksCount > 0)
            <span class="badge bg-danger badge-notification">{{ $pendingTasksCount }}</span>
        @endif
    </a>
    <a href="{{ route('student.timetable') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'student.timetable') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i> Horário
    </a>
    <a href="{{ route('student.attendances') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'student.attendances') ? 'active' : '' }}">
        <i class="fas fa-clipboard-check"></i> Frequência
    </a>

@elseif($role === 'teacher')
    <!-- Menu do Professor -->
    <div class="nav-section-title">Ensino</div>
    <a href="{{ route('teacher.classes') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'teacher.classes') ? 'active' : '' }}">
        <i class="fas fa-chalkboard-teacher"></i> Minhas Turmas
    </a>
    <a href="{{ route('teacher.subjects') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'teacher.subjects') ? 'active' : '' }}">
        <i class="fas fa-book"></i> Disciplinas
    </a>
    <a href="{{ route('teacher.grades') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'teacher.grades') ? 'active' : '' }}">
        <i class="fas fa-edit"></i> Lançar Notas
    </a>
    <a href="{{ route('teacher.tasks') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'teacher.tasks') ? 'active' : '' }}">
        <i class="fas fa-tasks"></i> Tarefas
    </a>
    <a href="{{ route('teacher.attendance') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'teacher.attendance') ? 'active' : '' }}">
        <i class="fas fa-clipboard-check"></i> Frequência
    </a>
    <a href="{{ route('teacher.students') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'teacher.students') ? 'active' : '' }}">
        <i class="fas fa-users"></i> Alunos
    </a>

@elseif($role === 'parent')
    <!-- Menu do Responsável -->
    <div class="nav-section-title">Acompanhamento</div>
    <a href="{{ route('parent.children') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'parent.children') ? 'active' : '' }}">
        <i class="fas fa-child"></i> Meus Educandos
    </a>
    <a href="{{ route('parent.progress') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'parent.progress') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i> Progresso
    </a>
    <a href="{{ route('parent.attendances') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'parent.attendances') ? 'active' : '' }}">
        <i class="fas fa-user-check"></i> Frequência
    </a>
    <a href="{{ route('parent.payments') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'parent.payments') ? 'active' : '' }}">
        <i class="fas fa-credit-card"></i> Mensalidades
    </a>
    <a href="{{ route('parent.messages') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'parent.messages') ? 'active' : '' }}">
        <i class="fas fa-envelope"></i> Mensagens
    </a>

@elseif($role === 'secretary')
    <!-- Menu da Secretaria -->
    <div class="nav-section-title">Gestão Acadêmica</div>
    <a href="{{ route('secretary.registrations') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'secretary.registrations') ? 'active' : '' }}">
        <i class="fas fa-user-plus"></i> Inscrições
        @if($pendingRegistrationsCount > 0)
            <span class="badge bg-danger badge-notification">{{ $pendingRegistrationsCount }}</span>
        @endif
    </a>
    <a href="{{ route('secretary.students') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'secretary.students') ? 'active' : '' }}">
        <i class="fas fa-graduation-cap"></i> Alunos
    </a>
    <a href="{{ route('secretary.teachers') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'secretary.teachers') ? 'active' : '' }}">
        <i class="fas fa-chalkboard-teacher"></i> Professores
    </a>
    <a href="{{ route('secretary.courses') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'secretary.courses') ? 'active' : '' }}">
        <i class="fas fa-school"></i> Turmas
    </a>
    <a href="{{ route('secretary.subjects') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'secretary.subjects') ? 'active' : '' }}">
        <i class="fas fa-book"></i> Disciplinas
    </a>
    <div class="nav-section-title">Administração</div>
    <a href="{{ route('secretary.reports') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'secretary.reports') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i> Relatórios
    </a>
    <a href="{{ route('secretary.events') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'secretary.events') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i> Eventos
    </a>
    <a href="{{ route('secretary.finance') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'secretary.finance') ? 'active' : '' }}">
        <i class="fas fa-money-bill-wave"></i> Financeiro
    </a>

@elseif($role === 'admin')
    <!-- Menu do Administrador -->
    <div class="nav-section-title">Gestão de Usuários</div>
    <a href="{{ route('admin.users') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'admin.users') ? 'active' : '' }}">
        <i class="fas fa-user-cog"></i> Usuários
    </a>
    <a href="{{ route('admin.roles') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'admin.roles') ? 'active' : '' }}">
        <i class="fas fa-user-tag"></i> Permissões
    </a>
    <div class="nav-section-title">Configurações</div>
    <a href="{{ route('admin.settings') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'admin.settings') ? 'active' : '' }}">
        <i class="fas fa-cogs"></i> Sistema
    </a>
    <a href="{{ route('admin.email') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'admin.email') ? 'active' : '' }}">
        <i class="fas fa-envelope"></i> Email
    </a>
    <a href="{{ route('admin.notifications') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'admin.notifications') ? 'active' : '' }}">
        <i class="fas fa-bell"></i> Notificações
    </a>
    <div class="nav-section-title">Monitoramento</div>
    <a href="{{ route('admin.logs') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'admin.logs') ? 'active' : '' }}">
        <i class="fas fa-history"></i> Logs do Sistema
    </a>
    <a href="{{ route('admin.backup') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'admin.backup') ? 'active' : '' }}">
        <i class="fas fa-database"></i> Backup
    </a>
    <a href="{{ route('admin.monitoring') }}"
       class="nav-link {{ str_starts_with($currentRoute, 'admin.monitoring') ? 'active' : '' }}">
        <i class="fas fa-desktop"></i> Monitoramento
    </a>
@endif

<!-- Comunicação (Comum a todos os roles autenticados) -->
@if(in_array($role, ['student', 'teacher', 'parent', 'secretary', 'admin']))
<div class="nav-section-title mt-4">Comunicação</div>
<a href="{{ route('messages.index') }}"
   class="nav-link {{ str_starts_with($currentRoute, 'messages') ? 'active' : '' }}">
    <i class="fas fa-comments"></i> Mensagens
    @if($unreadMessagesCount > 0)
        <span class="badge bg-danger badge-notification">{{ $unreadMessagesCount }}</span>
    @endif
</a>
<a href="{{ route('blog.index') }}"
   class="nav-link {{ str_starts_with($currentRoute, 'blog') ? 'active' : '' }}">
    <i class="fas fa-newspaper"></i> Blog/Notícias
</a>
<a href="{{ route('events.index') }}"
   class="nav-link {{ str_starts_with($currentRoute, 'events') ? 'active' : '' }}">
    <i class="fas fa-calendar-check"></i> Eventos
</a>
<a href="{{ route('calendar.index') }}"
   class="nav-link {{ str_starts_with($currentRoute, 'calendar') ? 'active' : '' }}">
    <i class="fas fa-calendar-alt"></i> Calendário
</a>
@endif

<!-- Perfil e Configurações (Comum a todos) -->
<div class="nav-section-title mt-4">Minha Conta</div>
<a href="{{ route('profile') }}"
   class="nav-link {{ str_starts_with($currentRoute, 'profile') ? 'active' : '' }}">
    <i class="fas fa-user"></i> Meu Perfil
</a>
<a href="{{ route('settings') }}"
   class="nav-link {{ str_starts_with($currentRoute, 'settings') ? 'active' : '' }}">
    <i class="fas fa-cog"></i> Configurações
</a>

<!-- Ajuda -->
<div class="nav-section-title mt-4">Suporte</div>
<a href="{{ route('help') }}"
   class="nav-link {{ str_starts_with($currentRoute, 'help') ? 'active' : '' }}">
    <i class="fas fa-question-circle"></i> Ajuda
</a>
<a href="{{ route('contact') }}"
   class="nav-link {{ str_starts_with($currentRoute, 'contact') ? 'active' : '' }}">
    <i class="fas fa-headset"></i> Contactar Suporte
</a>

<!-- Versão do Sistema (apenas admin) -->
@if($role === 'admin')
<div class="nav-section-title mt-4">Sistema</div>
<div class="px-4 py-3">
    <div class="text-white-50 small">
        <div class="mb-1">
            <i class="fas fa-code-branch me-1"></i>
            Versão: 1.0.0
        </div>
        <div>
            <i class="fas fa-database me-1"></i>
            Ambiente: {{ config('app.env') }}
        </div>
    </div>
</div>
@endif
