<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Concluído - Portal Escolar Alegria Pedro</title>

    @vite(['resources/css/auth.css'])
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">

    <style>
        .success-container {
            max-width: 500px;
            margin: 0 auto;
        }

        .success-card {
            text-align: center;
            padding: 3rem 2rem;
        }

        .success-icon {
            font-size: 5rem;
            color: var(--ip-success-color);
            margin-bottom: 2rem;
            animation: bounce 1s ease infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .success-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--ip-primary-blue);
        }

        .success-message {
            color: var(--ip-text-light);
            line-height: 1.6;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        .next-steps {
            background: var(--ip-light-bg);
            border-radius: var(--bs-border-radius-lg);
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: left;
        }

        .next-steps h5 {
            color: var(--ip-primary-blue);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--ip-border-color);
        }

        .step:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .step-number {
            width: 30px;
            height: 30px;
            background: var(--ip-primary-blue);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .step-content h6 {
            color: var(--ip-text-dark);
            margin-bottom: 0.25rem;
        }

        .step-content p {
            color: var(--ip-text-light);
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        .user-info-card {
            background: white;
            border: 2px solid var(--ip-border-color);
            border-radius: var(--bs-border-radius-lg);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--ip-border-color);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 500;
            color: var(--ip-text-dark);
        }

        .info-value {
            color: var(--ip-primary-blue);
            font-weight: 500;
        }

        .timer {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ip-success-color);
            margin: 1rem 0;
        }

        .contact-info {
            background: rgba(30, 58, 138, 0.05);
            border-radius: var(--bs-border-radius-lg);
            padding: 1.5rem;
            margin-top: 2rem;
        }

        @media (max-width: 576px) {
            .success-card {
                padding: 2rem 1rem;
            }

            .success-icon {
                font-size: 4rem;
            }

            .success-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-bg-pattern"></div>

    <div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
        <div class="success-container animate-fade-in">
            <div class="auth-card shadow-lg">
                <div class="success-card">
                    <div class="success-icon">
                        <i class="lni lni-checkmark-circle"></i>
                    </div>

                    <h1 class="success-title">Registro Concluído!</h1>

                    <div class="success-message">
                        <p>Sua conta foi criada com sucesso e está aguardando aprovação da administração do Instituto Alegria Pedro.</p>
                        <p>Você receberá um email de confirmação quando sua conta for aprovada.</p>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success">
                        <i class="lni lni-checkmark-circle"></i>
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(auth()->check())
                    <div class="user-info-card">
                        <h5 class="mb-3">
                            <i class="lni lni-user"></i>
                            Suas Informações
                        </h5>
                        <div class="info-item">
                            <span class="info-label">Nome:</span>
                            <span class="info-value">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tipo de Conta:</span>
                            <span class="info-value">
                                @if(auth()->user()->hasRole('student'))
                                    <span class="badge bg-primary">Aluno</span>
                                @elseif(auth()->user()->hasRole('teacher'))
                                    <span class="badge bg-success">Professor</span>
                                @elseif(auth()->user()->hasRole('guardian'))
                                    <span class="badge bg-info">Responsável</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="info-value">
                                <span class="badge bg-warning">
                                    <i class="lni lni-timer"></i>
                                    Aguardando Aprovação
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Data do Registro:</span>
                            <span class="info-value">{{ auth()->user()->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="next-steps">
                        <h5>
                            <i class="lni lni-list"></i>
                            Próximos Passos
                        </h5>

                        <div class="step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h6>Aprovação da Administração</h6>
                                <p>Sua conta será revisada pela equipe administrativa do instituto.</p>
                            </div>
                        </div>

                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h6>Email de Confirmação</h6>
                                <p>Você receberá um email quando sua conta for aprovada.</p>
                            </div>
                        </div>

                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h6>Acesso ao Sistema</h6>
                                <p>Após aprovação, você poderá fazer login e acessar todas as funcionalidades.</p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-info">
                        <h5 class="mb-3">
                            <i class="lni lni-support"></i>
                            Precisa de Ajuda?
                        </h5>
                        <p class="small mb-2">
                            Entre em contato com a administração do instituto:
                        </p>
                        <p class="small mb-2">
                            <i class="lni lni-envelope"></i>
                            <strong>Email:</strong> admin@institutopolicia.edu.ao
                        </p>
                        <p class="small mb-0">
                            <i class="lni lni-phone"></i>
                            <strong>Telefone:</strong> +244 222 000 000
                        </p>
                    </div>

                    <div class="mt-4">
                        <div class="d-grid gap-2">
                            <a href="{{ route('pending-approval') }}" class="btn btn-ip-primary">
                                <i class="lni lni-dashboard"></i>
                                Acompanhar Status da Conta
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="lni lni-exit"></i>
                                    Sair da Conta
                                </button>
                            </form>

                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="lni lni-enter"></i>
                                Ir para Página de Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small mb-0">
                    © {{ date('Y') }} Instituto Alegria Pedro - Portal Escolar
                </p>
                <p class="text-muted small mb-0">
                    <i class="lni lni-timer"></i>
                    Tempo médio de aprovação: 24-48 horas
                </p>
            </div>
        </div>
    </div>

    <script>
        // Contador de tempo desde o registro
        document.addEventListener('DOMContentLoaded', function() {
            @if(auth()->check() && auth()->user()->created_at)
                const registerTime = new Date('{{ auth()->user()->created_at }}');
                const now = new Date();
                const diffMs = now - registerTime;
                const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

                const timerElement = document.getElementById('timer');
                if (timerElement) {
                    timerElement.textContent = `${diffHours}h ${diffMinutes}m`;
                }
            @endif

            // Auto-redirect após 30 segundos para a página de status
            setTimeout(() => {
                window.location.href = "{{ route('pending-approval') }}";
            }, 30000);
        });
    </script>
</body>
</html>
