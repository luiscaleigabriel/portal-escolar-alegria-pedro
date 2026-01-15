<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Portal - Alegria Pedro | Cadastro</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="auth-wrapper">
        <div class="auth-box">

            <div class="text-center mb-4">
                <h4>Criar Conta</h4>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="input-style-1">
                    <label>Nome</label>
                    <input type="text" name="name" required>
                </div>

                <div class="input-style-1">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="input-style-1">
                    <label>Senha</label>
                    <input type="password" name="password" required>
                </div>

                <div class="input-style-1">
                    <label>Confirmar Senha</label>
                    <input type="password" name="password_confirmation" required>
                </div>

                <button class="main-btn primary-btn btn-hover w-100">Registrar</button>

            </form>

        </div>
    </div>

</body>

</html>
