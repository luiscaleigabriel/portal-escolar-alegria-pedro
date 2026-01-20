@extends('layouts.app')

@section('title', 'Painel Administrativo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Painel Administrativo</h1>
            <p class="mb-0 text-muted">Bem-vindo, {{ auth()->user()->name }}! Gerencie o sistema escolar.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.pending') }}" class="btn btn-warning">
                <i class="lni lni-timer me-1"></i>
                Pendentes: {{ $stats['pending_users'] }}
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                <i class="lni lni-users me-1"></i>
                Ver Todos
            </a>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Total de Usuários</h6>
                            <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                        </div>
                        <i class="lni lni-users display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-primary border-top-0">
                    <a href="{{ route('admin.users.index') }}" class="small text-white stretched-link">
                        Ver Detalhes
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Alunos</h6>
                            <h2 class="mb-0">{{ $stats['total_students'] }}</h2>
                        </div>
                        <i class="lni lni-graduation display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-success border-top-0">
                    <a href="{{ route('students.index') }}" class="small text-white stretched-link">
                        Ver Alunos
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Professores</h6>
                            <h2 class="mb-0">{{ $stats['total_teachers'] }}</h2>
                        </div>
                        <i class="lni lni-users display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-warning border-top-0">
                    <a href="{{ route('teachers.index') }}" class="small text-white stretched-link">
                        Ver Professores
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Turmas</h6>
                            <h2 class="mb-0">{{ $stats['total_turmas'] }}</h2>
                        </div>
                        <i class="lni lni-layers display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-info border-top-0">
                    <a href="{{ route('turmas.index') }}" class="small text-white stretched-link">
                        Ver Turmas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Duas colunas principais -->
    <div class="row g-4">
        <!-- Coluna da Esquerda: Registros Recentes -->
        <div class="col-xl-8">
            <div class="card ip-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="lni lni-alarm-clock me-2"></i>
                        Registros Recentes
                    </h6>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                        Ver Todos
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Perfil</th>
                                    <th>Status</th>
                                    <th>Registro</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_registrations'] as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title bg-light rounded-circle text-primary">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <span>{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                        <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                                        @endforeach
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <small>{{ $user->created_at->format('d/m/Y') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $user->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="btn btn-outline-info"
                                               title="Ver Detalhes"
                                               data-bs-toggle="tooltip">
                                                <i class="lni lni-eye"></i>
                                            </a>
                                            @if($user->status === 'pending')
                                            <a href="{{ route('admin.users.approve', $user) }}"
                                               class="btn btn-outline-success"
                                               onclick="return confirm('Aprovar este usuário?')"
                                               title="Aprovar"
                                               data-bs-toggle="tooltip">
                                                <i class="lni lni-checkmark-circle"></i>
                                            </a>
                                            <a href="{{ route('admin.users.reject', $user) }}"
                                               class="btn btn-outline-danger"
                                               onclick="return confirm('Rejeitar este usuário?')"
                                               title="Rejeitar"
                                               data-bs-toggle="tooltip">
                                                <i class="lni lni-cross-circle"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="lni lni-empty-file display-4 mb-3"></i>
                                        <p>Nenhum registro recente encontrado.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Gráficos de Estatísticas -->
            <div class="card ip-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-stats-up me-2"></i>
                        Estatísticas por Perfil
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @foreach($stats['by_role'] as $role => $count)
                        <div class="col-md-3 col-6">
                            <div class="text-center p-3 border rounded">
                                @php
                                    $roleIcons = [
                                        'student' => 'lni-graduation',
                                        'teacher' => 'lni-users',
                                        'guardian' => 'lni-user',
                                        'admin' => 'lni-cog',
                                        'director' => 'lni-star'
                                    ];
                                    $roleColors = [
                                        'student' => 'primary',
                                        'teacher' => 'warning',
                                        'guardian' => 'info',
                                        'admin' => 'danger',
                                        'director' => 'success'
                                    ];
                                @endphp
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-{{ $roleColors[$role] }}-subtle text-{{ $roleColors[$role] }} rounded-circle">
                                        <i class="lni {{ $roleIcons[$role] }} fs-4"></i>
                                    </div>
                                </div>
                                <h5 class="mb-1">{{ $count }}</h5>
                                <small class="text-muted">{{ ucfirst($role) }}s</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna da Direita: Ações Rápidas e Status -->
        <div class="col-xl-4">
            <!-- Ações Rápidas -->
            <div class="card ip-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-bolt me-2"></i>
                        Ações Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.users.pending') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                    <i class="lni lni-timer"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Aprovar Usuários</h6>
                                <p class="mb-0 text-muted small">{{ $stats['pending_users'] }} pendentes</p>
                            </div>
                            <i class="lni lni-arrow-right text-muted"></i>
                        </a>

                        <a href="{{ route('students.create') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                    <i class="lni lni-graduation"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Adicionar Aluno</h6>
                                <p class="mb-0 text-muted small">Criar novo registro</p>
                            </div>
                            <i class="lni lni-arrow-right text-muted"></i>
                        </a>

                        <a href="{{ route('teachers.create') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                    <i class="lni lni-users"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Adicionar Professor</h6>
                                <p class="mb-0 text-muted small">Criar novo registro</p>
                            </div>
                            <i class="lni lni-arrow-right text-muted"></i>
                        </a>

                        <a href="{{ route('turmas.create') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                    <i class="lni lni-layers"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Criar Turma</h6>
                                <p class="mb-0 text-muted small">Nova turma escolar</p>
                            </div>
                            <i class="lni lni-arrow-right text-muted"></i>
                        </a>

                        <a href="{{ route('admin.chat.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <i class="lni lni-comments"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Ver Chats</h6>
                                <p class="mb-0 text-muted small">Monitorar conversas</p>
                            </div>
                            <i class="lni lni-arrow-right text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status do Sistema -->
            <div class="card ip-card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-checkmark-circle me-2"></i>
                        Status do Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                        <i class="lni lni-checkmark"></i>
                                    </div>
                                </div>
                                <h5 class="mb-1">{{ $stats['approved'] }}</h5>
                                <small class="text-muted">Aprovados</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                        <i class="lni lni-timer"></i>
                                    </div>
                                </div>
                                <h5 class="mb-1">{{ $stats['pending'] }}</h5>
                                <small class="text-muted">Pendentes</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                        <i class="lni lni-cross-circle"></i>
                                    </div>
                                </div>
                                <h5 class="mb-1">{{ $stats['rejected'] }}</h5>
                                <small class="text-muted">Rejeitados</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-secondary-subtle text-secondary rounded-circle">
                                        <i class="lni lni-ban"></i>
                                    </div>
                                </div>
                                <h5 class="mb-1">{{ $stats['suspended'] }}</h5>
                                <small class="text-muted">Suspensos</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Última atualização: {{ now()->format('d/m/Y H:i') }}</small>
                        <a href="{{ route('admin.users.stats') }}" class="btn btn-sm btn-outline-primary">
                            Mais Estatísticas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
