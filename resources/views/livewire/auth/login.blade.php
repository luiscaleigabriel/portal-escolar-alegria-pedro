@section('title', 'Login')
<div>
    <div class="auth-card row g-0">
        <div class="col-md-5 auth-info d-none d-md-flex">
            <div>
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo-img mx-auto">
                <h3 class="mb-3">Bem-vindo ao Portal</h3>
                <p class="mb-0">Instituto Politécnico Privado Alegria Pedro. Excelência no ensino e inovação
                    tecnológica.
                </p>
            </div>
        </div>

        <div class="col-md-7 auth-form">
            <div class="text-center mb-4 d-md-none">
                <h2 class="text-primary fw-bold">IPP Alegria Pedro</h2>
            </div>

            <h4 class="fw-bold mb-3">Acesse sua conta</h4>
            <p class="text-muted mb-4">Selecione seu perfil e insira suas credenciais.</p>

            <form wire:submit.prevent="login">
                @if (session('message'))
                    <div class="alert alert-success fade-in">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('message') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger fade-in">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Eu sou...</label>
                    <select class="form-select mb-3" wire:model="role">
                        <option value="student">Aluno</option>
                        <option value="teacher">Professor</option>
                        <option value="parent">Responsável</option>
                        <option value="secretary">Secretaria</option>
                        <option value="admin">Administrador</option>
                    </select>
                    @error('role')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <label class="form-label">Como deseja entrar?</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" wire:model="login_type" id="email_login"
                                value="email">
                            <label class="form-check-label" for="email_login">Email</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" wire:model="login_type" id="phone_login"
                                value="phone">
                            <label class="form-check-label" for="phone_login">Telefone</label>
                        </div>
                    </div>

                    @if ($login_type === 'email')
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" placeholder="Digite seu email"
                                wire:model="email">
                        </div>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    @else
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                            <input type="text" class="form-control" placeholder="Digite seu telefone"
                                wire:model="phone">
                        </div>
                        @error('phone')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Senha</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" placeholder="••••••••" wire:model="password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword(this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" wire:model="remember">
                        <label class="form-check-label" for="remember">Lembrar-me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-decoration-none text-primary small">Esqueceu a
                        senha?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3" wire:loading.attr="disabled">
                    <span wire:loading.remove>Entrar no Portal</span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                        Entrando...
                    </span>
                </button>

                <p class="text-center text-muted">Ainda não tem conta?
                    <a href="{{ route('register') }}" class="text-primary fw-bold">Solicitar Inscrição</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(button) {
            const input = button.parentElement.querySelector('input');
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Auto-dismiss toasts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.hide();
                }, 5000);
            });
        });
    </script>
</div>
