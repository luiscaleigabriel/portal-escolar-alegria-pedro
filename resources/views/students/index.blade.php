@extends('layouts.app')

@section('title', 'Gerenciar Alunos')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Gerenciamento de Alunos</h1>
            <p class="mb-0 text-muted">Gerencie todos os alunos do sistema</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="lni lni-arrow-left me-1"></i>
                Voltar
            </a>
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="lni lni-plus me-1"></i>
                Novo Aluno
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
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="lni lni-checkmark display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $activeStudents }}</h2>
                    <p class="text-muted mb-0">Ativos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                            <i class="lni lni-timer display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $pendingStudents }}</h2>
                    <p class="text-muted mb-0">Sem Turma</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                            <i class="lni lni-layers display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $totalTurmas }}</h2>
                    <p class="text-muted mb-0">Turmas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card ip-card mb-4">
        <div class="card-body">
            <form action="{{ route('students.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Nome, matrícula ou documento...">
                </div>
                <div class="col-md-3">
                    <label for="turma_id" class="form-label">Turma</label>
                    <select class="form-select" id="turma_id" name="turma_id">
                        <option value="">Todas</option>
                        @foreach($turmas as $turma)
                        <option value="{{ $turma->id }}" {{ request('turma_id') == $turma->id ? 'selected' : '' }}>
                            {{ $turma->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos</option>
                        <option value="with_turma" {{ request('status') == 'with_turma' ? 'selected' : '' }}>Com Turma</option>
                        <option value="without_turma" {{ request('status') == 'without_turma' ? 'selected' : '' }}>Sem Turma</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="lni lni-search-alt me-1"></i>
                            Filtrar
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                            <i class="lni lni-reload"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Alunos -->
    <div class="card ip-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Lista de Alunos</h6>
            <span class="badge bg-primary">{{ $students->total() }} alunos</span>
        </div>
        <div class="card-body">
            @if($students->isEmpty())
            <div class="text-center py-5">
                <div class="avatar-lg mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle">
                        <i class="lni lni-graduation display-4"></i>
                    </div>
                </div>
                <h5 class="mb-2">Nenhum aluno encontrado</h5>
                <p class="text-muted">Tente ajustar os filtros de busca.</p>
                <a href="{{ route('students.create') }}" class="btn btn-primary mt-3">
                    <i class="lni lni-plus me-1"></i>
                    Adicionar Aluno
                </a>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Informações</th>
                            <th>Turma</th>
                            <th>Responsáveis</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $student->user->name }}</h6>
                                        <small class="text-muted">{{ $student->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small class="d-block">
                                    <i class="lni lni-id-card me-1"></i>
                                    {{ $student->identity_document ?? 'Não informado' }}
                                </small>
                                <small class="d-block mt-1">
                                    <i class="lni lni-agenda me-1"></i>
                                    {{ $student->registration_number ?? 'Sem matrícula' }}
                                </small>
                                <small class="d-block mt-1">
                                    <i class="lni lni-phone me-1"></i>
                                    {{ $student->user->phone ?? 'Não informado' }}
                                </small>
                            </td>
                            <td>
                                @if($student->turma)
                                <span class="badge bg-primary">
                                    {{ $student->turma->name }}
                                </span>
                                @else
                                <span class="badge bg-warning">Sem turma</span>
                                @endif
                            </td>
                            <td>
                                @if($student->guardians->isNotEmpty())
                                <div class="d-flex flex-column gap-1">
                                    @foreach($student->guardians->take(2) as $guardian)
                                    <small class="text-muted">
                                        <i class="lni lni-user"></i>
                                        {{ $guardian->user->name }}
                                    </small>
                                    @endforeach
                                    @if($student->guardians->count() > 2)
                                    <small class="text-muted">
                                        +{{ $student->guardians->count() - 2 }} mais
                                    </small>
                                    @endif
                                </div>
                                @else
                                <span class="badge bg-secondary">Nenhum</span>
                                @endif
                            </td>
                            <td>
                                @if($student->user->isApproved())
                                <span class="badge bg-success">Ativo</span>
                                @elseif($student->user->isPending())
                                <span class="badge bg-warning">Pendente</span>
                                @else
                                <span class="badge bg-danger">Inativo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('students.show', $student) }}"
                                       class="btn btn-outline-info"
                                       title="Ver Detalhes"
                                       data-bs-toggle="tooltip">
                                        <i class="lni lni-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}"
                                       class="btn btn-outline-warning"
                                       title="Editar"
                                       data-bs-toggle="tooltip">
                                        <i class="lni lni-pencil-alt"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-outline-danger"
                                            onclick="deleteStudent({{ $student->id }}, '{{ $student->user->name }}')"
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
                        Mostrando {{ $students->firstItem() }} a {{ $students->lastItem() }}
                        de {{ $students->total() }} registros
                    </span>
                </div>
                <div>
                    {{ $students->links() }}
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
                    <h5 class="modal-title">Excluir Aluno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir o aluno <strong id="deleteStudentName"></strong>?</p>
                    <div class="alert alert-danger">
                        <i class="lni lni-warning me-2"></i>
                        Esta ação não pode ser desfeita. Todas as notas, faltas e registros associados serão removidos.
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

        window.deleteStudent = function(studentId, studentName) {
            document.getElementById('deleteStudentName').textContent = studentName;
            document.getElementById('deleteForm').action = `/students/${studentId}`;
            deleteModal.show();
        };
    });
</script>
@endpush
@endsection
