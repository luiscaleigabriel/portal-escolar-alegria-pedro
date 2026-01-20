<aside class="sidebar-nav-wrapper">
    <div class="navbar-logo">
        <a href="{{ auth()->user()->hasRole(['admin', 'director']) ? route('admin.dashboard') : route('dashboard') }}">
            <img src="{{ Vite::asset('resources/template/images/logo/logo.svg') }}">
        </a>
    </div>

    <nav class="sidebar-nav">
        <ul>
            @if(auth()->user()->hasRole(['admin', 'director']))
                <!-- Menu Admin -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="lni lni-dashboard"></i>
                        <span class="nav-text">Dashboard Admin</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.pending') }}">
                        <i class="lni lni-timer"></i>
                        <span class="nav-text">Aprovações</span>
                        @if($pendingCount = \App\Models\User::where('status', 'pending')->count())
                        <span class="badge bg-warning float-end">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="lni lni-users"></i>
                        <span class="nav-text">Todos Usuários</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('students.index') }}">
                        <i class="lni lni-graduation"></i>
                        <span class="nav-text">Alunos</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('teachers.index') }}">
                        <i class="lni lni-users"></i>
                        <span class="nav-text">Professores</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('turmas.index') }}">
                        <i class="lni lni-layers"></i>
                        <span class="nav-text">Turmas</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.chat.index') }}">
                        <i class="lni lni-comments"></i>
                        <span class="nav-text">Chats</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('subjects.index') }}">
                        <i class="lni lni-book"></i>
                        <span class="nav-text">Disciplinas</span>
                    </a>
                </li>

            @elseif(auth()->user()->hasRole('teacher'))
                <!-- Menu Professor -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="lni lni-dashboard"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('chat.index') }}">
                        <i class="lni lni-comments"></i>
                        <span class="nav-text">Chat</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="lni lni-book"></i>
                        <span class="nav-text">Minhas Turmas</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="lni lni-agenda"></i>
                        <span class="nav-text">Lançar Notas</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="lni lni-alarm-clock"></i>
                        <span class="nav-text">Registrar Faltas</span>
                    </a>
                </li>

            @elseif(auth()->user()->hasRole('student'))
                <!-- Menu Aluno -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="lni lni-dashboard"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('chat.index') }}">
                        <i class="lni lni-comments"></i>
                        <span class="nav-text">Chat</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="lni lni-agenda"></i>
                        <span class="nav-text">Minhas Notas</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="lni lni-alarm-clock"></i>
                        <span class="nav-text">Minhas Faltas</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="lni lni-calendar"></i>
                        <span class="nav-text">Horários</span>
                    </a>
                </li>

            @elseif(auth()->user()->hasRole('guardian'))
                <!-- Menu Responsável -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="lni lni-dashboard"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('chat.index') }}">
                        <i class="lni lni-comments"></i>
                        <span class="nav-text">Chat</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="lni lni-graduation"></i>
                        <span class="nav-text">Meus Alunos</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="lni lni-agenda"></i>
                        <span class="nav-text">Boletins</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="lni lni-alarm-clock"></i>
                        <span class="nav-text">Faltas</span>
                    </a>
                </li>
            @endif

            <!-- Menu comum a todos -->
            <li class="nav-item">
                <a href="{{ route('profile.edit') }}">
                    <i class="lni lni-user"></i>
                    <span class="nav-text">Meu Perfil</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
