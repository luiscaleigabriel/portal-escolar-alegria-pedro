<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - IPP Alegria Pedro</title>

    <!-- Bootstrap 5 -->
    <link href="{{ asset('assets/css/bootstrap.rtl.min.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    @yield('styles')


    <!-- Livewire Styles -->
    @livewireStyles

</head>

<body>
    <!-- Sidebar -->
    <nav id="sidebar" class="fade-in">
        <div class="sidebar-header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo IPP"
                onerror="this.src='https://ui-avatars.com/api/?name=IPP&background=003399&color=fff&size=128'">
            <h6 class="fw-bold mb-0">IPP Alegria Pedro</h6>
            <small class="text-white-50">Portal Acadêmico</small>
        </div>

        <div class="user-info">
            @auth
                <img src="{{ auth()->user()->full_photo_url }}" alt="Avatar" class="user-avatar"
                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0055ff&color=fff&size=128'">
                <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                <small class="text-white-50">
                    @switch(auth()->user()->role)
                        @case('admin')
                            Administrador
                        @break

                        @case('secretary')
                            Secretaria
                        @break

                        @case('teacher')
                            Professor
                        @break

                        @case('student')
                            Aluno
                        @break

                        @case('parent')
                            Responsável
                        @break
                    @endswitch
                </small>
            @endauth
        </div>

        <div class="py-3">
            @includeWhen(auth()->check(), 'layouts.partials.sidebar-menu')
        </div>

        <div class="sidebar-footer">
            <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-light w-100"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i> Sair
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div id="content">
        <!-- Top Navbar -->
        <header class="top-navbar slide-in">
            <div class="d-flex align-items-center">
                <button type="button" id="sidebarCollapse" class="btn d-lg-none me-3">
                    <i class="fas fa-bars fs-5 text-muted"></i>
                </button>
                <div class="page-title">
                    <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
                    <div class="page-subtitle">@yield('page-subtitle', 'Bem-vindo ao Portal Acadêmico')</div>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <!-- Notifications -->
                <div class="dropdown me-3 notifications">
                    <button class="btn btn-light rounded-circle p-2" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell text-muted"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 300px;">
                        <div class="p-3 border-bottom">
                            <h6 class="mb-0">Notificações</h6>
                        </div>
                        <div style="max-height: 300px; overflow-y: auto;">
                            <a href="#" class="dropdown-item d-flex align-items-center p-3 border-bottom">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-graduation-cap text-primary me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small text-muted">Novas notas disponíveis</div>
                                    <div class="fw-medium">Matemática - 1º Trimestre</div>
                                </div>
                            </a>
                            <!-- Add more notifications -->
                        </div>
                        <div class="p-2 border-top">
                            <a href="#" class="btn btn-sm btn-link w-100 text-center">Ver todas</a>
                        </div>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                        data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->full_photo_url }}" class="rounded-circle" width="45"
                            height="45" alt="User"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0055ff&color=fff'">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user me-2"></i> Meu Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i> Configurações
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Sair
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @yield('js')

    <!-- Livewire Scripts -->
    @livewireScripts

</body>

</html>
