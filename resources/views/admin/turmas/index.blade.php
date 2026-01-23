@extends('layouts.admin')

@section('title', 'Gestão de Turmas')

@section('cssjs')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Turmas</li>
</ol>
@endsection

@section('content')
<!-- Cabeçalho -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Gestão de Turmas</h1>
        <p class="text-muted mb-0">Gerencie todas as turmas do sistema</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.turmas.create') }}" class="btn btn-primary">
            <i class="lni lni-plus me-1"></i>
            Nova Turma
        </a>
    </div>
</div>

<!-- Cards de Estatísticas -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card ip-card border-start border-primary border-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted fw-normal">Total de Turmas</h6>
                        <h3 class="mb-0">{{ $turmas->total() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="lni lni-layers display-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card ip-card border-start border-success border-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted fw-normal">Turmas Ativas</h6>
                        <h3 class="mb-0">{{ $turmas->where('status', 'active')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="lni lni-checkmark-circle display-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card ip-card border-start border-info border-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted fw-normal">Total de Alunos</h6>
                        <h3 class="mb-0">{{ $turmas->sum(fn($t) => $t->students->count()) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="lni lni-users display-4 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card ip-card border-start border-warning border-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted fw-normal">Vagas Disponíveis</h6>
                        <h3 class="mb-0">{{ $turmas->sum('capacity') - $turmas->sum(fn($t) => $t->students->count()) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="lni lni-empty-file display-4 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Turmas -->
<div class="card ip-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Turma</th>
                        <th>Professor</th>
                        <th>Alunos</th>
                        <th>Capacidade</th>
                        <th>Ano Letivo</th>
                        <th>Status</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($turmas as $turma)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                            <i class="lni lni-layers"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $turma->name }}</h6>
                                        <small class="text-muted">{{ $turma->grade_level }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($turma->teacher)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs me-2">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                            <i class="lni lni-user"></i>
                                        </div>
                                    </div>
                                    <span>{{ $turma->teacher->user->name }}</span>
                                </div>
                                @else
                                <span class="text-muted">Não atribuído</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary">{{ $turma->students->count() }}/{{ $turma->capacity }}</span>
                                    @if($turma->students->count() > 0)
                                    <small class="text-muted ms-2">
                                        {{ round(($turma->students->count() / $turma->capacity) * 100) }}%
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $turma->capacity }}</td>
                            <td>{{ $turma->school_year }}</td>
                            <td>
                                @if($turma->status === 'active')
                                <span class="badge bg-success">Ativa</span>
                                @else
                                <span class="badge bg-warning">Inativa</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="lni lni-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.turmas.show', $turma) }}">
                                                <i class="lni lni-eye me-2"></i> Ver Detalhes
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.turmas.edit', $turma) }}">
                                                <i class="lni lni-pencil me-2"></i> Editar
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $turma->id }}">
                                                <i class="lni lni-trash me-2"></i> Remover
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal de Remoção -->
                        <div class="modal fade" id="deleteModal{{ $turma->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.turmas.destroy', $turma) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Remover Turma</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Tem certeza que deseja remover a turma <strong>{{ $turma->name }}</strong>?</p>
                                            <div class="alert alert-warning">
                                                <i class="lni lni-warning me-2"></i>
                                                Todos os alunos serão removidos desta turma.
                                            </div>
                                            <div class="alert alert-danger">
                                                <i class="lni lni-warning me-2"></i>
                                                Esta ação não pode ser desfeita.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">Confirmar Remoção</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="lni lni-layers display-4"></i>
                                    <p class="mt-2">Nenhuma turma encontrada</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        @if($turmas->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Mostrando {{ $turmas->firstItem() }} a {{ $turmas->lastItem() }} de {{ $turmas->total() }} resultados
            </div>
            <div>
                {{ $turmas->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
