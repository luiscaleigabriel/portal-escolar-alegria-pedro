<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Portal - Alegria Pedro | Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="auth-wrapper">
        <div class="auth-box">

            <div class="text-center mb-4">
                <img src="{{ Vite::asset('resources/assets/images/logo/logo.svg') }}" width="120">
                <h4 class="mt-3">Portal Escolar MP10</h4>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-style-1">
                    <label>Email</label>
                    <input type="email" name="email" required autofocus>
                </div>

                <div class="input-style-1">
                    <label>Senha</label>
                    <input type="password" name="password" required>
                </div>

                <button class="main-btn primary-btn btn-hover w-100">Entrar</button>

                <div class="text-center mt-3">
                    <a href="{{ route('register') }}">Criar conta</a>
                </div>

            </form>

        </div>
    </div>

</body>

</html>
