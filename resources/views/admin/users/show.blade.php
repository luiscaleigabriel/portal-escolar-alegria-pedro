@extends('layouts.app')
@section('title', 'Detalhes do Usuário')
@section('cssjs')
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
@endsection
@section('content')
    <div class="admin-wrapper">
        @include('admin.partials.sidebar')
        <!-- Conteúdo Principal -->
        <main class="admin-main">
             <!-- Header -->
            @include('admin.partials.header')
            <div class="container-fluid px-4">
                <!-- Cabeçalho -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
                            </ol>
                        </nav>
                        <h1 class="h3 mb-0 text-gray-800">Detalhes do Usuário</h1>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="lni lni-arrow-left me-1"></i>
                            Voltar
                        </a>
                        @if ($user->isPending())
                            <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Aprovar este usuário?')">
                                    <i class="lni lni-checkmark-circle me-1"></i>
                                    Aprovar
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="lni lni-cross-circle me-1"></i>
                                Rejeitar
                            </button>
                        @endif
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Coluna da Esquerda: Informações do Usuário -->
                    <div class="col-xl-8">
                        <!-- Informações Gerais -->
                        <div class="card ip-card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="lni lni-user me-2"></i>
                                    Informações Gerais
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 text-center mb-4">
                                        <div class="avatar-xl mx-auto mb-3">
                                            @php
                                                $role = $user->roles->first();
                                                $roleName = $role ? $role->name : 'unknown';
                                                $roleIcons = [
                                                    'student' => 'lni-graduation',
                                                    'teacher' => 'lni-users',
                                                    'guardian' => 'lni-user',
                                                    'admin' => 'lni-cog',
                                                    'director' => 'lni-star',
                                                ];
                                                $roleColors = [
                                                    'student' => 'primary',
                                                    'teacher' => 'warning',
                                                    'guardian' => 'info',
                                                    'admin' => 'danger',
                                                    'director' => 'success',
                                                ];
                                            @endphp
                                            <div
                                                class="avatar-title bg-{{ $roleColors[$roleName] ?? 'secondary' }}-subtle
                                text-{{ $roleColors[$roleName] ?? 'secondary' }} rounded-circle">
                                                <i class="lni {{ $roleIcons[$roleName] ?? 'lni-user' }} display-4"></i>
                                            </div>
                                        </div>
                                        <div>
                                            @foreach ($user->roles as $role)
                                                <span class="badge bg-{{ $roleColors[$role->name] ?? 'secondary' }} mb-1">
                                                    {{ ucfirst($role->name) }}
                                                </span>
                                            @endforeach
                                            <div class="mt-2">
                                                @switch($user->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">Pendente</span>
                                                    @break

                                                    @case('approved')
                                                        <span class="badge bg-success">Aprovado</span>
                                                    @break

                                                    @case('rejected')
                                                        <span class="badge bg-danger">Rejeitado</span>
                                                    @break

                                                    @case('suspended')
                                                        <span class="badge bg-secondary">Suspenso</span>
                                                    @break
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Nome Completo</label>
                                                <p class="mb-0">{{ $user->name }}</p>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Email</label>
                                                <p class="mb-0">
                                                    <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                                        {{ $user->email }}
                                                    </a>
                                                    @if ($user->email_verified_at)
                                                        <span class="badge bg-success-subtle text-success ms-1">
                                                            <i class="lni lni-checkmark"></i> Verificado
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning ms-1">
                                                            <i class="lni lni-warning"></i> Não verificado
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Telefone</label>
                                                <p class="mb-0">{{ $user->phone ?? 'Não informado' }}</p>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Data de Nascimento</label>
                                                <p class="mb-0">
                                                    {{ $user->birth_date?->format('d/m/Y') ?? 'Não informada' }}
                                                    @if ($user->birth_date)
                                                        <small class="text-muted ms-2">
                                                            ({{ \Carbon\Carbon::parse($user->birth_date)->age }} anos)
                                                        </small>
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Gênero</label>
                                                <p class="mb-0">
                                                    {{ [
                                                        'male' => 'Masculino',
                                                        'female' => 'Feminino',
                                                        'other' => 'Outro',
                                                        'prefer_not_to_say' => 'Prefiro não dizer',
                                                    ][$user->gender] ?? 'Não informado' }}
                                                </p>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Data de Registro</label>
                                                <p class="mb-0">
                                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                                    <small class="text-muted ms-2">
                                                        ({{ $user->created_at->diffForHumans() }})
                                                    </small>
                                                </p>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label text-muted small mb-1">Endereço</label>
                                                <p class="mb-0">{{ $user->address ?? 'Não informado' }}</p>
                                            </div>

                                            @if ($user->emergency_contact)
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-1">Contato de
                                                        Emergência</label>
                                                    <p class="mb-0">{{ $user->emergency_contact }}</p>
                                                </div>
                                            @endif

                                            @if ($user->approved_at)
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-1">Data de
                                                        Aprovação</label>
                                                    <p class="mb-0">
                                                        {{ $user->approved_at->format('d/m/Y H:i') }}
                                                        @if ($user->approver)
                                                            <small class="text-muted ms-2">
                                                                por {{ $user->approver->name }}
                                                            </small>
                                                        @endif
                                                    </p>
                                                </div>
                                            @endif

                                            @if ($user->status === 'rejected' && $user->rejection_reason)
                                                <div class="col-12">
                                                    <label class="form-label text-muted small mb-1">Motivo da
                                                        Rejeição</label>
                                                    <div class="alert alert-danger py-2">
                                                        <i class="lni lni-warning me-2"></i>
                                                        {{ $user->rejection_reason }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Perfil Específico -->
                        @if ($profile)
                            <div class="card ip-card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        @php
                                            $role = $user->roles->first();
                                            $roleIcons = [
                                                'student' => 'lni-graduation',
                                                'teacher' => 'lni-users',
                                                'guardian' => 'lni-user',
                                            ];
                                        @endphp
                                        <i class="lni {{ $roleIcons[$role->name] ?? 'lni-user' }} me-2"></i>
                                        Informações de {{ ucfirst($role->name) }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if ($user->hasRole('student') && $profile)
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Documento de
                                                    Identificação</label>
                                                <p class="mb-0">{{ $profile->identity_document ?? 'Não informado' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Número de Matrícula</label>
                                                <p class="mb-0">{{ $profile->registration_number ?? 'Não atribuído' }}
                                                </p>
                                            </div>
                                            @if ($profile->turma)
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-1">Turma</label>
                                                    <p class="mb-0">
                                                        <a href="{{ route('turmas.show', $profile->turma) }}"
                                                            class="text-decoration-none">
                                                            {{ $profile->turma->name }}
                                                        </a>
                                                    </p>
                                                </div>
                                            @endif
                                            @if ($profile->guardians->isNotEmpty())
                                                <div class="col-12">
                                                    <label class="form-label text-muted small mb-1">Responsáveis</label>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach ($profile->guardians as $guardian)
                                                            <span class="badge bg-info">
                                                                {{ $guardian->user->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($user->hasRole('teacher') && $profile)
                                        <div class="alert alert-info">
                                            <i class="lni lni-information me-2"></i>
                                            Perfil de professor criado. As turmas e disciplinas serão atribuídas pela
                                            administração.
                                        </div>
                                    @elseif($user->hasRole('guardian') && $profile)
                                        @if ($profile->students->isNotEmpty())
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="form-label text-muted small mb-1">Alunos
                                                        Vinculados</label>
                                                    <div class="list-group">
                                                        @foreach ($profile->students as $student)
                                                            <a href="{{ route('students.show', $student) }}"
                                                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <h6 class="mb-1">{{ $student->user->name }}</h6>
                                                                    <small class="text-muted">
                                                                        Matrícula:
                                                                        {{ $student->registration_number ?? 'N/A' }}
                                                                    </small>
                                                                </div>
                                                                @if ($student->turma)
                                                                    <span class="badge bg-primary">
                                                                        {{ $student->turma->name }}
                                                                    </span>
                                                                @endif
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="lni lni-warning me-2"></i>
                                                Nenhum aluno vinculado. Contate a administração para fazer os vínculos
                                                necessários.
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Coluna da Direita: Ações e Histórico -->
                    <div class="col-xl-4">
                        <!-- Status e Ações -->
                        <div class="card ip-card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="lni lni-cog me-2"></i>
                                    Status e Ações
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label text-muted small mb-1">Status Atual</label>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        @switch($user->status)
                                            @case('pending')
                                                <span class="badge bg-warning py-2 px-3">
                                                    <i class="lni lni-timer me-1"></i>
                                                    Pendente de Aprovação
                                                </span>
                                            @break

                                            @case('approved')
                                                <span class="badge bg-success py-2 px-3">
                                                    <i class="lni lni-checkmark me-1"></i>
                                                    Aprovado
                                                </span>
                                            @break

                                            @case('rejected')
                                                <span class="badge bg-danger py-2 px-3">
                                                    <i class="lni lni-cross-circle me-1"></i>
                                                    Rejeitado
                                                </span>
                                            @break

                                            @case('suspended')
                                                <span class="badge bg-secondary py-2 px-3">
                                                    <i class="lni lni-ban me-1"></i>
                                                    Suspenso
                                                </span>
                                            @break
                                        @endswitch
                                    </div>

                                    @if ($user->isPending())
                                        <div class="d-grid gap-2">
                                            <form action="{{ route('admin.users.approve', $user) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success"
                                                    onclick="return confirm('Aprovar este usuário?')">
                                                    <i class="lni lni-checkmark-circle me-1"></i>
                                                    Aprovar
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#rejectModal">
                                                <i class="lni lni-cross-circle me-1"></i>
                                                Rejeitar Usuário
                                            </button>
                                        </div>
                                    @elseif($user->isApproved())
                                        <div class="d-grid gap-2">
                                            @if ($user->hasRole('student'))
                                                <a href="#" class="btn btn-outline-primary">
                                                    <i class="lni lni-layers me-1"></i>
                                                    Atribuir Turma
                                                </a>
                                            @endif
                                            @if ($user->hasRole('guardian') && $profile->students->isEmpty())
                                                <a href="#" class="btn btn-outline-info">
                                                    <i class="lni lni-users me-1"></i>
                                                    Vincular Alunos
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                                data-bs-target="#suspendModal">
                                                <i class="lni lni-ban me-1"></i>
                                                Suspender Usuário
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <!-- Informações de Moderação -->
                                @if ($user->approved_at || $user->rejection_reason)
                                    <div class="border-top pt-3">
                                        <label class="form-label text-muted small mb-1">Informações de Moderação</label>
                                        <ul class="list-unstyled small">
                                            @if ($user->approved_at)
                                                <li class="mb-2">
                                                    <i class="lni lni-checkmark-circle text-success me-1"></i>
                                                    <strong>Aprovado em:</strong>
                                                    {{ $user->approved_at->format('d/m/Y H:i') }}
                                                </li>
                                            @endif
                                            @if ($user->approver)
                                                <li class="mb-2">
                                                    <i class="lni lni-user text-primary me-1"></i>
                                                    <strong>Por:</strong> {{ $user->approver->name }}
                                                </li>
                                            @endif
                                            @if ($user->status === 'rejected' && $user->rejection_reason)
                                                <li class="mb-2">
                                                    <i class="lni lni-cross-circle text-danger me-1"></i>
                                                    <strong>Motivo:</strong> {{ $user->rejection_reason }}
                                                </li>
                                            @endif
                                            <li>
                                                <i class="lni lni-calendar text-muted me-1"></i>
                                                <strong>Registrado:</strong>
                                                {{ $user->created_at->format('d/m/Y') }}
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Informações de Contato -->
                        <div class="card ip-card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="lni lni-phone me-2"></i>
                                    Contato
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary">
                                        <i class="lni lni-envelope me-1"></i>
                                        Enviar Email
                                    </a>
                                    @if ($user->phone)
                                        <a href="tel:{{ $user->phone }}" class="btn btn-outline-success">
                                            <i class="lni lni-phone me-1"></i>
                                            Ligar para {{ $user->phone }}
                                        </a>
                                    @endif
                                    <button type="button" class="btn btn-outline-info" onclick="copyEmail()">
                                        <i class="lni lni-clipboard me-1"></i>
                                        Copiar Email
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Rejeição -->
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.users.reject', $user) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Rejeitar Usuário</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Você está prestes a rejeitar o usuário <strong>{{ $user->name }}</strong>.</p>
                                <div class="mb-3">
                                    <label for="rejectReason" class="form-label">Motivo da Rejeição <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="rejectReason" name="reason" rows="4" required
                                        placeholder="Informe o motivo da rejeição..."></textarea>
                                    <div class="form-text">Este motivo será enviado por email ao usuário.</div>
                                </div>
                                <div class="alert alert-warning">
                                    <i class="lni lni-warning me-2"></i>
                                    Esta ação não pode ser desfeita.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Confirmar Rejeição</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal de Suspensão -->
            <div class="modal fade" id="suspendModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.users.suspend', $user) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Suspender Usuário</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Você está prestes a suspender o usuário <strong>{{ $user->name }}</strong>.</p>
                                <div class="mb-3">
                                    <label for="suspendReason" class="form-label">Motivo da Suspensão <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="suspendReason" name="reason" rows="4" required
                                        placeholder="Informe o motivo da suspensão..."></textarea>
                                </div>
                                <div class="alert alert-warning">
                                    <i class="lni lni-warning me-2"></i>
                                    O usuário não poderá acessar o sistema enquanto estiver suspenso.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Confirmar Suspensão</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    </div>

    @push('scripts')
        <script>
            function copyEmail() {
                navigator.clipboard.writeText("{{ $user->email }}").then(() => {
                    alert('Email copiado para a área de transferência!');
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Inicializar tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
    @endpush
@endsection
