@section('title', 'Recuperar Senha')
@section('content')
<div class="auth-card row g-0">
    <div class="col-md-5 auth-info d-none d-md-flex">
        <div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img mx-auto" onerror="this.src='https://ui-avatars.com/api/?name=IPP&background=003399&color=fff&size=128'">
            <h3 class="mb-3">Esqueceu a Senha?</h3>
            <p class="mb-0">Não se preocupe! Vamos ajudá-lo a recuperar o acesso à sua conta.</p>
        </div>
    </div>

    <div class="col-md-7 auth-form">
        <div class="text-center mb-4 d-md-none">
            <h2 class="text-primary fw-bold">IPP Alegria Pedro</h2>
            <p class="text-muted">Recuperar Senha</p>
        </div>

        <h4 class="fw-bold mb-3">Recuperar Senha</h4>
        <p class="text-muted mb-4">Digite seu email para receber o link de recuperação</p>

        @if (session('status'))
            <div class="alert alert-success fade-in">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        @if ($status)
            <div class="alert alert-success fade-in">
                <i class="fas fa-check-circle me-2"></i>
                {{ $status }}
            </div>
        @endif

        <form wire:submit.prevent="sendResetLink">
            <div class="mb-4">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" placeholder="Digite seu email registrado" wire:model="email">
                </div>
                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3" wire:loading.attr="disabled">
                <span wire:loading.remove>Enviar Link de Recuperação</span>
                <span wire:loading>
                    <span class="spinner-border spinner-border-sm me-2"></span> Enviando...
                </span>
            </button>

            <p class="text-center text-muted mb-0">
                <a href="{{ route('login') }}" class="text-primary fw-medium">
                    <i class="fas fa-arrow-left me-1"></i> Voltar ao Login
                </a>
            </p>
        </form>
    </div>
</div>

<script>
// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
@endsection
