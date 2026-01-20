@extends('layouts.app')

@section('title', 'Painel do Aluno')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Painel do Aluno</h1>
            <p class="mb-0 text-muted">Bem-vindo, {{ $student->user->name }}!</p>
        </div>
        <div>
            <a href="{{ route('students.report-card', $student) }}" class="btn btn-primary">
                <i class="lni lni-printer me-1"></i>
                Imprimir Boletim
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
                            <h6 class="text-white-50 mb-2">Média Geral</h6>
                            <h2 class="mb-0">{{ $average_grade }}</h2>
                        </div>
                        <i class="lni lni-stats-up display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-primary border-top-0">
                    <span class="small text-white">
                        @if($grade_status['status'] == 'success')
                        <i class="lni lni-checkmark-circle me-1"></i>
                        {{ $grade_status['message'] }}
                        @elseif($grade_status['status'] == 'warning')
                        <i class="lni lni-warning me-1"></i>
                        {{ $grade_status['message'] }}
                        @else
                        <i class="lni lni-cross-circle me-1"></i>
                        {{ $grade_status['message'] }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Turma</h6>
                            <h2 class="mb-0">{{ $turma->name ?? 'N/A' }}</h2>
                        </div>
                        <i class="lni lni-layers display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-success border-top-0">
                    <span class="small text-white">
                        @if($turma)
                        <i class="lni lni-calendar me-1"></i>
                        Ano: {{ $turma->year }}
                        @else
                        <i class="lni lni-warning me-1"></i>
                        Sem turma atribuída
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Faltas</h6>
                            <h2 class="mb-0">{{ $total_absences }}</h2>
                        </div>
                        <i class="lni lni-alarm-clock display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-warning border-top-0">
                    <span class="small text-white">
                        <i class="lni lni-checkmark-circle me-1"></i>
                        {{ $justified_absences }} justificadas
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ip-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Próximas Provas</h6>
                            <h2 class="mb-0">{{ count($upcoming_exams) }}</h2>
                        </div>
                        <i class="lni lni-agenda display-6 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-info border-top-0">
                    <span class="small text-white">
                        <i class="lni lni-calendar me-1"></i>
                        {{ count($upcoming_exams) > 0 ? 'Próxima: ' . $upcoming_exams[0]['date'] : 'Nenhuma agendada' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Duas colunas principais -->
    <div class="row g-4">
        <!-- Coluna da Esquerda: Notas Recentes -->
        <div class="col-xl-8">
            <div class="card ip-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="lni lni-agenda me-2"></i>
                        Últimas Notas
                    </h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    @if($grades->isEmpty())
                    <div class="text-center py-5">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle">
                                <i class="lni lni-agenda display-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-2">Nenhuma nota registrada</h5>
                        <p class="text-muted">Suas notas aparecerão aqui quando forem lançadas.</p>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Disciplina</th>
                                    <th>Nota</th>
                                    <th>Status</th>
                                    <th>Período</th>
                                    <th>Data</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grades as $grade)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                                    <i class="lni lni-book"></i>
                                                </div>
                                            </div>
                                            <span>{{ $grade->subject->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $grade->value >= 10 ? 'success' : 'danger' }} p-2">
                                            <h5 class="mb-0">{{ number_format($grade->value, 1) }}</h5>
                                        </span>
                                    </td>
                                    <td>
                                        @if($grade->value >= 14)
                                        <span class="badge bg-success">Excelente</span>
                                        @elseif($grade->value >= 10)
                                        <span class="badge bg-warning">Bom</span>
                                        @else
                                        <span class="badge bg-danger">Precisa Melhorar</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $grade->term }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $grade->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="tooltip"
                                                title="Ver detalhes">
                                            <i class="lni lni-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Faltas Recentes -->
            <div class="card ip-card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-alarm-clock me-2"></i>
                        Últimas Faltas
                    </h6>
                </div>
                <div class="card-body">
                    @if($absences->isEmpty())
                    <div class="text-center py-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle">
                                <i class="lni lni-checkmark-circle display-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-2">Nenhuma falta registrada</h5>
                        <p class="text-muted">Parabéns pela frequência!</p>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Disciplina</th>
                                    <th>Justificada</th>
                                    <th>Professor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($absences as $absence)
                                <tr>
                                    <td>
                                        <small>{{ $absence->date->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $absence->subject->name }}</span>
                                    </td>
                                    <td>
                                        @if($absence->justified)
                                        <span class="badge bg-success">
                                            <i class="lni lni-checkmark-circle me-1"></i>
                                            Sim
                                        </span>
                                        @else
                                        <span class="badge bg-danger">
                                            <i class="lni lni-cross-circle me-1"></i>
                                            Não
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $absence->subject->teachers->first()->user->name ?? 'N/A' }}
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            Total: {{ $total_absences }} faltas
                        </small>
                        <small class="text-muted">
                            Justificadas: {{ $justified_absences }}
                        </small>
                        <small class="text-muted">
                            Não justificadas: {{ $unjustified_absences }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna da Direita: Informações e Ações -->
        <div class="col-xl-4">
            <!-- Informações do Aluno -->
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
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                {{ strtoupper(substr($student->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $student->user->name }}</h5>
                        <p class="text-muted mb-2">{{ $student->user->email }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge bg-primary">
                                Matrícula: {{ $student->registration_number ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <div class="border-top pt-3">
                        <div class="row small mb-2">
                            <div class="col-6">
                                <span class="text-muted">Turma:</span>
                            </div>
                            <div class="col-6 text-end">
                                <strong>{{ $turma->name ?? 'N/A' }}</strong>
                            </div>
                        </div>
                        <div class="row small mb-2">
                            <div class="col-6">
                                <span class="text-muted">Ano:</span>
                            </div>
                            <div class="col-6 text-end">
                                <strong>{{ $turma->year ?? 'N/A' }}</strong>
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
                        <div class="row small">
                            <div class="col-6">
                                <span class="text-muted">Registro:</span>
                            </div>
                            <div class="col-6 text-end">
                                <small>{{ $student->user->created_at->format('d/m/Y') }}</small>
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
                            <i class="lni lni-agenda me-2"></i>
                            Ver Boletim Completo
                        </a>
                        <a href="#" class="btn btn-outline-warning text-start">
                            <i class="lni lni-calendar me-2"></i>
                            Ver Horários
                        </a>
                        <a href="#" class="btn btn-outline-info text-start">
                            <i class="lni lni-book me-2"></i>
                            Material de Estudo
                        </a>
                        <a href="#" class="btn btn-outline-secondary text-start">
                            <i class="lni lni-download me-2"></i>
                            Baixar Documentos
                        </a>
                    </div>
                </div>
            </div>

            <!-- Próximas Provas -->
            <div class="card ip-card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-alarm-clock me-2"></i>
                        Próximas Provas
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($upcoming_exams) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcoming_exams as $exam)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <small class="text-muted d-block">{{ $exam['date'] }}</small>
                                <span>{{ $exam['subject'] }}</span>
                            </div>
                            <span class="badge bg-{{ $exam['type'] == 'prova' ? 'danger' : 'warning' }}">
                                {{ $exam['type'] }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-3">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle">
                                <i class="lni lni-calendar display-4"></i>
                            </div>
                        </div>
                        <h6 class="mb-2">Nenhuma prova agendada</h6>
                        <p class="text-muted small mb-0">Suas próximas provas aparecerão aqui.</p>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-transparent">
                    <a href="#" class="btn btn-sm btn-outline-primary w-100">
                        <i class="lni lni-calendar me-1"></i>
                        Ver Calendário Completo
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
