@extends('layouts.app')

@section('title', 'Painel do Responsável')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Painel do Responsável</h1>
            <p class="mb-0 text-muted">Bem-vindo, {{ $guardian->user->name }}!</p>
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
                            <h6 class="text-white-50 mb-2">Alunos Vinculados</h6>
                            <h2 class="mb-0">{{ $total_students }}</h2>
                        </div>
                        <i class="lni lni-graduation display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-primary border-top-0">
                    <span class="small text-white">
                        <i class="lni lni-users me-1"></i>
                        {{ $total_students }} aluno(s) sob sua responsabilidade
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Média Geral</h6>
                            <h2 class="mb-0">{{ $average_grade_all }}</h2>
                        </div>
                        <i class="lni lni-stats-up display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-success border-top-0">
                    <span class="small text-white">
                        <i class="lni lni-checkmark-circle me-1"></i>
                        Média dos seus alunos
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Faltas Totais</h6>
                            <h2 class="mb-0">{{ $total_absences_all }}</h2>
                        </div>
                        <i class="lni lni-alarm-clock display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-warning border-top-0">
                    <span class="small text-white">
                        <i class="lni lni-alarm me-1"></i>
                        Total de faltas dos seus alunos
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Alertas</h6>
                            <h2 class="mb-0">{{ $alerts->count() }}</h2>
                        </div>
                        <i class="lni lni-warning display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-info border-top-0">
                    <span class="small text-white">
                        <i class="lni lni-alarm me-1"></i>
                        {{ $alerts->count() > 0 ? 'Atenção necessária' : 'Tudo em ordem' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas Importantes -->
    @if($alerts->count() > 0)
    <div class="card ip-card border-danger mb-4">
        <div class="card-header bg-danger text-white">
            <h6 class="mb-0">
                <i class="lni lni-warning me-2"></i>
                Alertas que Requerem Atenção
            </h6>
        </div>
        <div class="card-body">
            <div class="alert alert-danger">
                <i class="lni lni-warning me-2"></i>
                <strong>Atenção!</strong> Os seguintes alunos precisam de acompanhamento:
            </div>
            <div class="row g-3">
                @foreach($alerts as $alert)
                <div class="col-md-6">
                    <div class="card border-danger">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0 text-danger">{{ $alert['student']->user->name }}</h6>
                                <span class="badge bg-danger">{{ $alert['student']->turma->name ?? 'Sem turma' }}</span>
                            </div>
                            <div class="mb-3">
                                @foreach($alert['alerts'] as $alertType)
                                <span class="badge bg-danger me-1">
                                    <i class="lni lni-warning me-1"></i>
                                    {{ $alertType }}
                                </span>
                                @endforeach
                            </div>
                            <div class="row small text-muted">
                                <div class="col-6">
                                    <span>Média:</span>
                                    <strong class="text-danger">{{ number_format($alert['student']->average_grade, 1) }}</strong>
                                </div>
                                <div class="col-6">
                                    <span>Faltas:</span>
                                    <strong class="text-danger">{{ $alert['student']->total_absences }}</strong>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('students.show', $alert['student']) }}" class="btn btn-sm btn-outline-danger">
                                    <i class="lni lni-eye me-1"></i>
                                    Ver Detalhes
                                </a>
                                <a href="{{ route('chat.index') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="lni lni-comments me-1"></i>
                                    Contatar Professor
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Duas colunas principais -->
    <div class="row g-4">
        <!-- Coluna da Esquerda: Lista de Alunos -->
        <div class="col-xl-8">
            <div class="card ip-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="lni lni-graduation me-2"></i>
                        Meus Alunos
                    </h6>
                    <span class="badge bg-primary">{{ $total_students }} aluno(s)</span>
                </div>
                <div class="card-body">
                    @if($students->isEmpty())
                    <div class="text-center py-5">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle">
                                <i class="lni lni-graduation display-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-2">Nenhum aluno vinculado</h5>
                        <p class="text-muted">Entre em contato com a administração para vincular alunos.</p>
                    </div>
                    @else
                    <div class="row g-3">
                        @foreach($students as $student)
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="mb-0">{{ $student->user->name }}</h6>
                                        <span class="badge bg-primary">{{ $student->turma->name ?? 'Sem turma' }}</span>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-1">
                                            <i class="lni lni-id-card me-1"></i>
                                            Matrícula: {{ $student->registration_number ?? 'N/A' }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="lni lni-phone me-1"></i>
                                            {{ $student->user->phone ?? 'Não informado' }}
                                        </small>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <div class="avatar-sm mx-auto mb-2">
                                                    <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                                        <i class="lni lni-stats-up"></i>
                                                    </div>
                                                </div>
                                                <h6 class="mb-1">{{ number_format($student->average_grade, 1) }}</h6>
                                                <small class="text-muted">Média</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <div class="avatar-sm mx-auto mb-2">
                                                    <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                                        <i class="lni lni-alarm-clock"></i>
                                                    </div>
                                                </div>
                                                <h6 class="mb-1">{{ $student->total_absences }}</h6>
                                                <small class="text-muted">Faltas</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="lni lni-eye"></i>
                                            Ver
                                        </a>
                                        <a href="{{ route('students.report-card', $student) }}" class="btn btn-sm btn-outline-success">
                                            <i class="lni lni-printer"></i>
                                            Boletim
                                        </a>
                                        <a href="{{ route('chat.index') }}" class="btn btn-sm btn-outline-info">
                                            <i class="lni lni-comments"></i>
                                            Chat
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

            <!-- Boletins Recentes -->
            <div class="card ip-card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-files me-2"></i>
                        Boletins Disponíveis
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Turma</th>
                                    <th>Período</th>
                                    <th>Média</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <div class="avatar-title bg-light rounded-circle text-primary">
                                                    {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <span>{{ $student->user->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $student->turma->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">1º Bimestre 2024</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $student->average_grade >= 10 ? 'success' : 'danger' }}">
                                            {{ number_format($student->average_grade, 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($student->average_grade >= 14)
                                        <span class="badge bg-success">Excelente</span>
                                        @elseif($student->average_grade >= 10)
                                        <span class="badge bg-warning">Regular</span>
                                        @else
                                        <span class="badge bg-danger">Atenção</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('students.report-card', $student) }}"
                                               class="btn btn-outline-primary"
                                               title="Imprimir Boletim"
                                               data-bs-toggle="tooltip">
                                                <i class="lni lni-printer"></i>
                                            </a>
                                            <a href="{{ route('students.show', $student) }}"
                                               class="btn btn-outline-info"
                                               title="Ver Detalhes"
                                               data-bs-toggle="tooltip">
                                                <i class="lni lni-eye"></i>
                                            </a>
                                            <a href="{{ route('chat.index') }}"
                                               class="btn btn-outline-success"
                                               title="Contatar Professor"
                                               data-bs-toggle="tooltip">
                                                <i class="lni lni-comments"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna da Direita: Ações Rápidas e Informações -->
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
                        <a href="{{ route('chat.index') }}" class="btn btn-outline-primary text-start">
                            <i class="lni lni-comments me-2"></i>
                            Chat com Professores
                        </a>
                        <a href="#" class="btn btn-outline-success text-start">
                            <i class="lni lni-files me-2"></i>
                            Ver Todos os Boletins
                        </a>
                        <a href="#" class="btn btn-outline-warning text-start">
                            <i class="lni lni-calendar me-2"></i>
                            Calendário Escolar
                        </a>
                        <a href="#" class="btn btn-outline-info text-start">
                            <i class="lni lni-alarm-clock me-2"></i>
                            Consultar Faltas
                        </a>
                        <a href="#" class="btn btn-outline-secondary text-start">
                            <i class="lni lni-download me-2"></i>
                            Baixar Documentos
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informações do Responsável -->
            <div class="card ip-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-user me-2"></i>
                        Meu Perfil
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-xl mx-auto mb-3">
                            <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                {{ strtoupper(substr($guardian->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $guardian->user->name }}</h5>
                        <p class="text-muted mb-2">{{ $guardian->user->email }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge bg-primary">{{ $total_students }} aluno(s)</span>
                        </div>
                    </div>
                    <div class="border-top pt-3">
                        <div class="row small mb-2">
                            <div class="col-6">
                                <span class="text-muted">Telefone:</span>
                            </div>
                            <div class="col-6 text-end">
                                <strong>{{ $guardian->user->phone ?? 'Não informado' }}</strong>
                            </div>
                        </div>
                        <div class="row small mb-2">
                            <div class="col-6">
                                <span class="text-muted">Status:</span>
                            </div>
                            <div class="col-6 text-end">
                                <span class="badge bg-success">Ativo</span>
                            </div>
                        </div>
                        <div class="row small mb-2">
                            <div class="col-6">
                                <span class="text-muted">Contato Emergência:</span>
                            </div>
                            <div class="col-6 text-end">
                                <small>{{ $guardian->user->emergency_contact ?? 'Não informado' }}</small>
                            </div>
                        </div>
                        <div class="row small">
                            <div class="col-6">
                                <span class="text-muted">Registro:</span>
                            </div>
                            <div class="col-6 text-end">
                                <small>{{ $guardian->user->created_at->format('d/m/Y') }}</small>
                            </div>
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

            <!-- Resumo de Desempenho -->
            <div class="card ip-card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-stats-up me-2"></i>
                        Resumo de Desempenho
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Média Geral dos Alunos</h6>
                        <div class="progress" style="height: 25px;">
                            @php
                                $percentage = min(100, ($average_grade_all / 20) * 100);
                                $color = $average_grade_all >= 14 ? 'success' : ($average_grade_all >= 10 ? 'warning' : 'danger');
                            @endphp
                            <div class="progress-bar bg-{{ $color }}"
                                 role="progressbar"
                                 style="width: {{ $percentage }}%">
                                <strong>{{ $average_grade_all }}</strong>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">0</small>
                            <small class="text-muted">10</small>
                            <small class="text-muted">20</small>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <h6>Distribuição de Médias</h6>
                        <div class="row small text-center">
                            <div class="col-4">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                        <i class="lni lni-checkmark-circle"></i>
                                    </div>
                                </div>
                                <h6 class="mb-1">{{ $students->where('average_grade', '>=', 14)->count() }}</h6>
                                <small class="text-muted">Excelente</small>
                            </div>
                            <div class="col-4">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                        <i class="lni lni-warning"></i>
                                    </div>
                                </div>
                                <h6 class="mb-1">{{ $students->where('average_grade', '>=', 10)->where('average_grade', '<', 14)->count() }}</h6>
                                <small class="text-muted">Regular</small>
                            </div>
                            <div class="col-4">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                        <i class="lni lni-cross-circle"></i>
                                    </div>
                                </div>
                                <h6 class="mb-1">{{ $students->where('average_grade', '<', 10)->count() }}</h6>
                                <small class="text-muted">Atenção</small>
                            </div>
                        </div>
                    </div>
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
