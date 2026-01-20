@extends('layouts.app')

@section('title', 'Painel do Professor')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Painel do Professor</h1>
            <p class="mb-0 text-muted">Bem-vindo, {{ $teacher->user->name }}!</p>
        </div>
        <div>
            <a href="{{ route('chat.index') }}" class="btn btn-primary">
                <i class="lni lni-comments me-1"></i>
                Chats
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
                            <h6 class="text-white-50 mb-2">Turmas</h6>
                            <h2 class="mb-0">{{ $total_turmas }}</h2>
                        </div>
                        <i class="lni lni-layers display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-primary border-top-0">
                    <a href="#" class="small text-white stretched-link">
                        Ver Minhas Turmas
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Disciplinas</h6>
                            <h2 class="mb-0">{{ $subjects }}</h2>
                        </div>
                        <i class="lni lni-book display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-success border-top-0">
                    <a href="#" class="small text-white stretched-link">
                        Ver Disciplinas
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Alunos</h6>
                            <h2 class="mb-0">{{ $total_students }}</h2>
                        </div>
                        <i class="lni lni-graduation display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-warning border-top-0">
                    <a href="#" class="small text-white stretched-link">
                        Ver Alunos
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Notas Pendentes</h6>
                            <h2 class="mb-0">{{ $grades_to_launch }}</h2>
                        </div>
                        <i class="lni lni-agenda display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-info border-top-0">
                    <a href="#" class="small text-white stretched-link">
                        Lançar Notas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Duas colunas principais -->
    <div class="row g-4">
        <!-- Coluna da Esquerda: Minhas Turmas -->
        <div class="col-xl-8">
            <div class="card ip-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="lni lni-layers me-2"></i>
                        Minhas Turmas
                    </h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    @if($turmas->isEmpty())
                    <div class="text-center py-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle">
                                <i class="lni lni-layers display-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-2">Nenhuma turma atribuída</h5>
                        <p class="text-muted">Entre em contato com a administração para receber atribuições.</p>
                    </div>
                    @else
                    <div class="row g-3">
                        @foreach($turmas as $turma)
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="mb-0">{{ $turma->name }}</h6>
                                        <span class="badge bg-primary">{{ $turma->students_count }} alunos</span>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-1">
                                            <i class="lni lni-calendar me-1"></i>
                                            Ano: {{ $turma->year }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="lni lni-users me-1"></i>
                                            Professores: {{ $turma->teachers_count }}
                                        </small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="lni lni-eye"></i>
                                            Ver
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-success">
                                            <i class="lni lni-agenda"></i>
                                            Notas
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-warning">
                                            <i class="lni lni-alarm-clock"></i>
                                            Faltas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Atividades Recentes -->
            <div class="card ip-card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-alarm-clock me-2"></i>
                        Notas Recentes Lançadas
                    </h6>
                </div>
                <div class="card-body">
                    @if($recent_grades->isEmpty())
                    <div class="text-center py-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle">
                                <i class="lni lni-agenda display-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-2">Nenhuma nota lançada recentemente</h5>
                        <p class="text-muted">Comece a lançar notas para seus alunos.</p>
                        <a href="#" class="btn btn-primary mt-3">
                            <i class="lni lni-plus me-1"></i>
                            Lançar Primeira Nota
                        </a>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Disciplina</th>
                                    <th>Nota</th>
                                    <th>Período</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_grades as $grade)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <div class="avatar-title bg-light rounded-circle text-primary">
                                                    {{ strtoupper(substr($grade->student->user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <span>{{ $grade->student->user->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $grade->subject->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $grade->value >= 10 ? 'success' : 'danger' }}">
                                            {{ number_format($grade->value, 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $grade->term }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $grade->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-warning" title="Editar">
                                                <i class="lni lni-pencil-alt"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" title="Excluir">
                                                <i class="lni lni-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Coluna da Direita: Ações Rápidas e Próximas Atividades -->
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
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary text-start">
                            <i class="lni lni-agenda me-2"></i>
                            Lançar Notas
                        </a>
                        <a href="#" class="btn btn-outline-warning text-start">
                            <i class="lni lni-alarm-clock me-2"></i>
                            Registrar Faltas
                        </a>
                        <a href="{{ route('chat.index') }}" class="btn btn-outline-success text-start">
                            <i class="lni lni-comments me-2"></i>
                            Iniciar Chat
                        </a>
                        <a href="#" class="btn btn-outline-info text-start">
                            <i class="lni lni-calendar me-2"></i>
                            Ver Calendário
                        </a>
                        <a href="#" class="btn btn-outline-secondary text-start">
                            <i class="lni lni-files me-2"></i>
                            Material Didático
                        </a>
                    </div>
                </div>
            </div>

            <!-- Próximas Atividades -->
            <div class="card ip-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-calendar me-2"></i>
                        Próximas Atividades
                    </h6>
                </div>
                <div class="card-body">
                    @if($upcoming_activities)
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <small class="text-muted d-block">Hoje, 10:00</small>
                                <span>Reunião de Pais - 9º Ano</span>
                            </div>
                            <span class="badge bg-primary">Online</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <small class="text-muted d-block">Amanhã, 08:30</small>
                                <span>Prova de Matemática</span>
                            </div>
                            <span class="badge bg-warning">Turma A</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <small class="text-muted d-block">Sexta, 14:00</small>
                                <span>Entrega de Trabalhos</span>
                            </div>
                            <span class="badge bg-success">2 disciplinas</span>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-3">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle">
                                <i class="lni lni-calendar display-4"></i>
                            </div>
                        </div>
                        <h6 class="mb-2">Nenhuma atividade agendada</h6>
                        <p class="text-muted small mb-0">Suas próximas atividades aparecerão aqui.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informações do Professor -->
            <div class="card ip-card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-user me-2"></i>
                        Meu Perfil
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-xl mx-auto mb-3">
                            <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                <i class="lni lni-users display-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $teacher->user->name }}</h5>
                        <p class="text-muted mb-2">{{ $teacher->user->email }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge bg-primary">{{ $total_turmas }} turmas</span>
                            <span class="badge bg-success">{{ $subjects }} disciplinas</span>
                        </div>
                    </div>
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Status:</small>
                            <span class="badge bg-success">Ativo</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Registro:</small>
                            <small>{{ $teacher->user->created_at->format('d/m/Y') }}</small>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Último acesso:</small>
                            <small>{{ now()->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="lni lni-pencil-alt me-1"></i>
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
