<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Alegria Pedro</title>

    <!-- CSS via Vite -->
    @vite(['resources/css/auth.css'])

    <!-- Ícones LineIcons -->
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">

    <style>
        /* Estilos inline mínimos para carregamento rápido */
        .auth-page {
            min-height: 100vh;
            background: #0F172A;
        }

        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #0F172A;
            color: white;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            border-top-color: #3B82F6;
            animation: spin 1s ease-in-out infinite;
            margin-right: 1rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="auth-page">
    <!-- Loading inicial -->
    <div class="loading" id="loading">
        <div class="spinner"></div>
        <span>Carregando Portal...</span>
    </div>

    <!-- Conteúdo principal -->
    <div id="authContent" style="display: none;">
        <!-- Background pattern -->
        <div class="auth-bg-pattern"></div>

        <!-- Partículas animadas -->
        <div class="auth-particles" id="particles"></div>

        <div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
            <div class="auth-card-container animate-fade-in" style="width: 100%; max-width: 440px;">
                <div class="auth-card shadow-lg">
                    <!-- Cabeçalho -->
                    <div class="auth-header">
                        <div class="auth-logo">
                            <div class="logo-circle">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="">
                            </div>
                            <h1 class="h2 text-white mb-2">PORTAL ESCOLAR</h1>
                            <p class="text-white-50 mb-0">
                                <span class="text-gradient-gold fw-bold">ALEGRIA PEDRO</span>
                            </p>
                        </div>
                    </div>

                    <!-- Corpo -->
                    <div class="p-4 p-md-5">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="lni lni-envelope"></i>
                                    Email
                                </label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       class="form-control auth-form-control"
                                       value="{{ old('email') }}"
                                       required
                                       autofocus
                                       placeholder="seu@email.com">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="lni lni-lock-alt"></i>
                                    Senha
                                </label>
                                <div class="input-group">
                                    <input type="password"
                                           id="password"
                                           name="password"
                                           class="form-control auth-form-control"
                                           required
                                           placeholder="••••••••">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="lni lni-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox"
                                       id="remember"
                                       name="remember"
                                       class="form-check-input">
                                <label class="form-check-label" for="remember">
                                    Lembrar-me
                                </label>
                            </div>

                            <button type="submit" class="btn btn-ip-primary w-100 auth-submit-btn" id="submitBtn">
                                <i class="lni lni-enter"></i>
                                <span>Entrar no Portal</span>
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="#" class="text-decoration-none">
                                <i class="lni lni-key"></i>
                                Esqueceu sua senha?
                            </a>
                        </div>

                        <div class="auth-separator mt-4">
                            <span>ou</span>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-3">Não tem uma conta?</p>
                            <a href="{{ route('register') }}" class="btn btn-ip-outline w-100">
                                <i class="lni lni-user"></i>
                                Criar nova conta
                            </a>
                        </div>

                        <div class="auth-footer mt-5">
                            <p class="text-muted small mb-2">
                                © {{ date('Y') }} Alegria Pedro. Todos os direitos reservados.
                            </p>
                            <p class="text-muted small mb-0">Versão 1.0.0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Esperar o DOM carregar
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar conteúdo e esconder loading
            setTimeout(() => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('authContent').style.display = 'block';

                // Criar partículas
                createParticles();

                // Inicializar tooltips do Bootstrap
                if (typeof bootstrap !== 'undefined') {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                }
            }, 500);

            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    eyeIcon.className = type === 'password' ? 'lni lni-eye' : 'lni lni-eye-off';
                });
            }

            // Form submission handler
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');

            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const originalHTML = submitBtn.innerHTML;

                    submitBtn.innerHTML = `
                        <div class="auth-loader"></div>
                        <span>Entrando...</span>
                    `;
                    submitBtn.disabled = true;

                    // Reset após 10 segundos (caso algo dê errado)
                    setTimeout(() => {
                        submitBtn.innerHTML = originalHTML;
                        submitBtn.disabled = false;
                    }, 10000);
                });
            }

            // Função para criar partículas
            function createParticles() {
                const particlesContainer = document.getElementById('particles');
                if (!particlesContainer) return;

                const particleCount = 25;
                const colors = ['#60A5FA', '#3B82F6', '#FBBF24'];

                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';

                    // Tamanho aleatório
                    const size = Math.random() * 6 + 2;
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;

                    // Cor aleatória
                    const color = colors[Math.floor(Math.random() * colors.length)];
                    particle.style.backgroundColor = color;

                    // Posição inicial aleatória
                    particle.style.left = `${Math.random() * 100}%`;
                    particle.style.top = `${Math.random() * 100 + 100}%`;

                    // Animação
                    const duration = Math.random() * 15 + 10;
                    particle.style.animationDuration = `${duration}s`;
                    particle.style.animationDelay = `${Math.random() * 5}s`;

                    particlesContainer.appendChild(particle);
                }
            }
        });
    </script>

    <!-- Vite JS -->
    @vite(['resources/js/app.js'])
</body>
</html>
