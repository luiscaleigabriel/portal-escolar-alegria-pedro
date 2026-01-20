@extends('layouts.app')

@section('title', 'Gerenciar Turmas')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Gerenciamento de Turmas</h1>
            <p class="mb-0 text-muted">Gerencie todas as turmas do sistema</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="lni lni-arrow-left me-1"></i>
                Voltar
            </a>
            <a href="{{ route('turmas.create') }}" class="btn btn-primary">
                <i class="lni lni-plus me-1"></i>
                Nova Turma
            </a>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="lni lni-layers display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $totalTurmas }}</h2>
                    <p class="text-muted mb-0">Total de Turmas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="lni lni-graduation display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $totalStudents }}</h2>
                    <p class="text-muted mb-0">Total de Alunos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                            <i class="lni lni-users display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $totalTeachers }}</h2>
                    <p class="text-muted mb-0">Total de Professores</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                            <i class="lni lni-book display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $avgStudentsPerTurma }}</h2>
                    <p class="text-muted mb-0">Média Alunos/Turma</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card ip-card mb-4">
        <div class="card-body">
            <form action="{{ route('turmas.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Nome da turma...">
                </div>
                <div class="col-md-3">
                    <label for="year" class="form-label">Ano</label>
                    <select class="form-select" id="year" name="year">
                        <option value="">Todos</option>
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="teacher_id" class="form-label">Professor</label>
                    <select class="form-select" id="teacher_id" name="teacher_id">
                        <option value="">Todos</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="lni lni-search-alt me-1"></i>
                            Filtrar
                        </button>
                        <a href="{{ route('turmas.index') }}" class="btn btn-outline-secondary">
                            <i class="lni lni-reload"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Turmas -->
    <div class="card ip-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Lista de Turmas</h6>
            <span class="badge bg-primary">{{ $turmas->total() }} turmas</span>
        </div>
        <div class="card-body">
            @if($turmas->isEmpty())
            <div class="text-center py-5">
                <div class="avatar-lg mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle">
                        <i class="lni lni-layers display-4"></i>
                    </div>
                </div>
                <h5 class="mb-2">Nenhuma turma encontrada</h5>
                <p class="text-muted">Tente ajustar os filtros de busca.</p>
                <a href="{{ route('turmas.create') }}" class="btn btn-primary mt-3">
                    <i class="lni lni-plus me-1"></i>
                    Criar Turma
                </a>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Ano</th>
                            <th>Alunos</th>
                            <th>Professores</th>
                            <th>Disciplinas</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($turmas as $turma)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="lni lni-layers"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $turma->name }}</h6>
                                        <small class="text-muted">
                                            Criada em: {{ $turma->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $turma->year }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                        @php
                                            $capacity = 30; // Capacidade padrão
                                            $percentage = min(100, ($turma->students_count / $capacity) * 100);
                                            $color = $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : 'success');
                                        @endphp
                                        <div class="progress-bar bg-{{ $color }}"
                                             role="progressbar"
                                             style="width: {{ $percentage }}%">
                                        </div>
                                    </div>
                                    <span class="badge bg-primary">
                                        {{ $turma->students_count }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($turma->teachers_count > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge bg-warning">
                                        {{ $turma->teachers_count }} professor(es)
                                    </span>
                                </div>
                                @else
                                <span class="badge bg-secondary">Nenhum</span>
                                @endif
                            </td>
                            <td>
                                @if($turma->subjects_count > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge bg-info">
                                        {{ $turma->subjects_count }} disciplina(s)
                                    </span>
                                </div>
                                @else
                                <span class="badge bg-secondary">Nenhuma</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('turmas.show', $turma) }}"
                                       class="btn btn-outline-info"
                                       title="Ver Detalhes"
                                       data-bs-toggle="tooltip">
                                        <i class="lni lni-eye"></i>
                                    </a>
                                    <a href="{{ route('turmas.edit', $turma) }}"
                                       class="btn btn-outline-warning"
                                       title="Editar"
                                       data-bs-toggle="tooltip">
                                        <i class="lni lni-pencil-alt"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-outline-danger"
                                            onclick="deleteTurma({{ $turma->id }}, '{{ $turma->name }}')"
                                            title="Excluir"
                                            data-bs-toggle="tooltip">
                                        <i class="lni lni-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <span class="text-muted">
                        Mostrando {{ $turmas->firstItem() }} a {{ $turmas->lastItem() }}
                        de {{ $turmas->total() }} registros
                    </span>
                </div>
                <div>
                    {{ $turmas->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Excluir Turma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir a turma <strong id="deleteTurmaName"></strong>?</p>
                    <div class="alert alert-danger">
                        <i class="lni lni-warning me-2"></i>
                        Esta ação não pode ser desfeita. Todos os alunos serão desvinculados desta turma.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Modal de exclusão
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        window.deleteTurma = function(turmaId, turmaName) {
            document.getElementById('deleteTurmaName').textContent = turmaName;
            document.getElementById('deleteForm').action = `/turmas/${turmaId}`;
            deleteModal.show();
        };
    });
</script>
@endpush
@endsection
