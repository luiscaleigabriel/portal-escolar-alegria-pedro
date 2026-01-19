<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conta Rejeitada - Portal Alegria Pedro</title>

    @vite(['resources/css/auth.css'])
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">
</head>
<body class="auth-page">
    <div class="auth-bg-pattern"></div>

    <div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
        <div class="auth-card-container" style="max-width: 500px;">
            <div class="auth-card shadow-lg">
                <div class="text-center p-5">
                    <div class="mb-4">
                        <i class="lni lni-cross-circle" style="font-size: 4rem; color: #EF4444;"></i>
                    </div>

                    <h2 class="mb-3">Conta Rejeitada</h2>

                    @auth
                    @if(auth()->user()->rejection_reason)
                    <div class="alert alert-danger">
                        <strong>Motivo:</strong>
                        <p class="mb-0 mt-2">{{ auth()->user()->rejection_reason }}</p>
                    </div>
                    @endif
                    @endauth

                    <p class="text-muted mb-4">
                        Sua conta foi rejeitada pela administração do instituto.
                        Entre em contato para mais informações.
                    </p>

                    <div class="d-grid gap-2">
                        <a href="mailto:admin@institutopolicia.edu.ao" class="btn btn-ip-primary">
                            <i class="lni lni-envelope"></i>
                            Entrar em Contato
                        </a>

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
        </div>
    </div>
</body>
</html>
