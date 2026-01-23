@extends('layouts.admin')

@section('title', 'Gestão de Professores')

@section('cssjs')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Professores</li>
</ol>
@endsection

@section('content')
<!-- Cabeçalho -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Gestão de Professores</h1>
        <p class="text-muted mb-0">Gerencie todos os professores do sistema</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
            <i class="lni lni-plus me-1"></i>
            Novo Professor
        </a>
    </div>
</div>

<!-- Cards de Estatísticas -->
<div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="card ip-card border-start border-primary border-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted fw-normal">Total de Professores</h6>
                        <h3 class="mb-0">{{ $teachers->total() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="lni lni-teacher display-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card ip-card border-start border-success border-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted fw-normal">Ativos</h6>
                        <h3 class="mb-0">{{ $teachers->where('status', 'active')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="lni lni-checkmark-circle display-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card ip-card border-start border-warning border-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted fw-normal">Com Turmas</h6>
                        <h3 class="mb-0">{{ $teachers->filter(fn($t) => $t->turmas->count() > 0)->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="lni lni-layers display-4 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Professores -->
<div class="card ip-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Professor</th>
                        <th>Título Acadêmico</th>
                        <th>Disciplinas</th>
                        <th>Turmas</th>
                        <th>Status</th>
                        <th>Data de Contratação</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                            <i class="lni lni-user"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $teacher->user->name }}</h6>
                                        <small class="text-muted">{{ $teacher->user->email }}</small>
                                        @if($teacher->user->phone)
                                        <small class="text-muted d-block">{{ $teacher->user->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $teacher->academic_degree ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if($teacher->subjects->count() > 0)
                                    @foreach($teacher->subjects->take(2) as $subject)
                                        <span class="badge bg-info mb-1">{{ $subject->name }}</span>
                                    @endforeach
                                    @if($teacher->subjects->count() > 2)
                                        <span class="badge bg-light text-dark">+{{ $teacher->subjects->count() - 2 }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">Nenhuma</span>
                                @endif
                            </td>
                            <td>
                                @if($teacher->turmas->count() > 0)
                                    @foreach($teacher->turmas->take(2) as $turma)
                                        <span class="badge bg-primary mb-1">{{ $turma->name }}</span>
                                    @endforeach
                                    @if($teacher->turmas->count() > 2)
                                        <span class="badge bg-light text-dark">+{{ $teacher->turmas->count() - 2 }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">Nenhuma</span>
                                @endif
                            </td>
                            <td>
                                @switch($teacher->status)
                                    @case('active')
                                        <span class="badge bg-success">Ativo</span>
                                        @break
                                    @case('inactive')
                                        <span class="badge bg-warning">Inativo</span>
                                        @break
                                    @case('on_leave')
                                        <span class="badge bg-info">Afastado</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <small>{{ $teacher->hire_date->format('d/m/Y') }}</small>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="lni lni-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.teachers.show', $teacher) }}">
                                                <i class="lni lni-eye me-2"></i> Ver Detalhes
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.teachers.edit', $teacher) }}">
                                                <i class="lni lni-pencil me-2"></i> Editar
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $teacher->id }}">
                                                <i class="lni lni-trash me-2"></i> Remover
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal de Remoção -->
                        <div class="modal fade" id="deleteModal{{ $teacher->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Remover Professor</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Tem certeza que deseja remover <strong>{{ $teacher->user->name }}</strong>?</p>
                                            <div class="alert alert-danger">
                                                <i class="lni lni-warning me-2"></i>
                                                Esta ação não pode ser desfeita. Todos os dados relacionados serão removidos.
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
                                    <i class="lni lni-teacher display-4"></i>
                                    <p class="mt-2">Nenhum professor encontrado</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        @if($teachers->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Mostrando {{ $teachers->firstItem() }} a {{ $teachers->lastItem() }} de {{ $teachers->total() }} resultados
            </div>
            <div>
                {{ $teachers->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
