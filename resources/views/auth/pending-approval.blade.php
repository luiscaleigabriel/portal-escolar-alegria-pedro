<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aguardando Aprovação - Portal Alegria Pedro</title>

    @vite(['resources/css/auth.css'])
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">

    <style>
        .pending-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .status-card {
            text-align: center;
            padding: 3rem 2rem;
        }

        .status-icon {
            font-size: 4rem;
            color: var(--ip-highlight-gold);
            margin-bottom: 1.5rem;
        }

        .status-icon.rejected {
            color: var(--ip-error-color);
        }

        .status-icon.suspended {
            color: var(--ip-text-light);
        }

        .status-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--ip-text-dark);
        }

        .status-message {
            color: var(--ip-text-light);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .user-info {
            background: var(--ip-light-bg);
            border-radius: var(--bs-border-radius-lg);
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
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
            color: var(--ip-text-light);
        }

        .contact-info {
            background: rgba(30, 58, 138, 0.05);
            border-radius: var(--bs-border-radius-lg);
            padding: 1.5rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-bg-pattern"></div>

    <div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
        <div class="pending-container animate-fade-in">
            <div style="background-color: rgba(30, 143, 255, 0.411)" class="auth-card shadow-lg">
                <div class="status-card">
                    <div class="status-icon">
                        <i class="lni lni-timer"></i>
                    </div>

                    <h1 class="status-title">Aguardando Aprovação</h1>

                    <div class="status-message">
                        <p>Sua conta foi criada com sucesso e está aguardando aprovação da administração do instituto.</p>
                        <p>Você receberá um email quando sua conta for aprovada.</p>
                    </div>

                    @auth
                    <div style="color: #272727" class="user-info">
                        <div class="info-item">
                            <span class="info-label">Nome:</span>
                            <span class="info-value">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Perfil:</span>
                            <span class="info-value">
                                @if(auth()->user()->hasRole('student')) Aluno
                                @elseif(auth()->user()->hasRole('teacher')) Professor
                                @elseif(auth()->user()->hasRole('guardian')) Responsável
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Data do Registro:</span>
                            <span class="info-value">{{ auth()->user()->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    @endauth

                    <div class="contact-info">
                        <h5 class="mb-3">
                            <i class="lni lni-support"></i>
                            Dúvidas ou Informações
                        </h5>
                        <p class="small mb-2">
                            Entre em contato com a administração:
                        </p>
                        <p class="small mb-0">
                            <i class="lni lni-envelope"></i>
                            admin@alegriapedro.edu.ao
                        </p>
                        <p class="small mb-0">
                            <i class="lni lni-phone"></i>
                            +244 222 000 000
                        </p>
                    </div>

                    <div class="mt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="lni lni-exit"></i>
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small mb-0">
                    © {{ date('Y') }} Portal Alegria Pedro
                </p>
            </div>
        </div>
    </div>
</body>
</html>
