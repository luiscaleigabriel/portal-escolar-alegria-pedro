@extends('layouts.app')

@section('title', 'Gestão de Usuários')
@section('cssjs')
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
@endsection
@section('content')
    <div class="admin-wrapper">
        @include('admin.partials.sidebar')
        <!-- Conteúdo Principal -->
        <main class="admin-main">
            <div class="container-fluid px-4">
                <!-- Cabeçalho -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Usuários</li>
                            </ol>
                        </nav>
                        <h1 class="h3 mb-0 text-gray-800">Gestão de Usuários</h1>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="lni lni-plus me-1"></i>
                            Novo Usuário
                        </a>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#filterModal">
                            <i class="lni lni-funnel me-1"></i>
                            Filtros
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="lni lni-download me-1"></i>
                                Exportar
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.users.export') }}?format=csv">CSV</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.export') }}?format=excel">Excel</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.export') }}?format=pdf">PDF</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Cards de Estatísticas -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card ip-card border-start border-primary border-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted fw-normal">Total de Usuários</h6>
                                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="lni lni-users display-4 text-primary"></i>
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
                                        <h6 class="text-muted fw-normal">Pendentes</h6>
                                        <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="lni lni-timer display-4 text-warning"></i>
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
                                        <h6 class="text-muted fw-normal">Aprovados</h6>
                                        <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="lni lni-checkmark-circle display-4 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card ip-card border-start border-danger border-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted fw-normal">Rejeitados</h6>
                                        <h3 class="mb-0">{{ $stats['rejected'] }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="lni lni-cross-circle display-4 text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barra de Ações -->
                <div class="card ip-card mb-4">
                    <div class="card-body">
                        <form id="bulkForm" action="{{ route('admin.users.bulk-actions') }}" method="POST">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label" for="selectAll">
                                            Selecionar Todos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <select class="form-select" name="action" id="bulkAction" required>
                                        <option value="">Ação em massa...</option>
                                        <option value="approve">Aprovar Selecionados</option>
                                        <option value="reject">Rejeitar Selecionados</option>
                                        <option value="suspend">Suspender Selecionados</option>
                                        <option value="delete">Remover Selecionados</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary" id="applyBulkAction">
                                        <i class="lni lni-checkmark-circle me-1"></i>
                                        Aplicar
                                    </button>
                                    <span id="selectedCount" class="ms-2 text-muted">0 selecionados</span>
                                </div>
                            </div>
                            <div id="reasonField" class="mt-3 d-none">
                                <textarea class="form-control" name="reason" rows="2" placeholder="Informe o motivo..."></textarea>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabela de Usuários -->
                <div class="card ip-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAllTable">
                                            </div>
                                        </th>
                                        <th>Usuário</th>
                                        <th>Perfil</th>
                                        <th>Status</th>
                                        <th>Verificado</th>
                                        <th>Registro</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input user-checkbox" type="checkbox"
                                                        name="users[]" value="{{ $user->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $role = $user->roles->first();
                                                        $roleColors = [
                                                            'student' => 'primary',
                                                            'teacher' => 'warning',
                                                            'guardian' => 'info',
                                                            'admin' => 'danger',
                                                            'director' => 'success',
                                                        ];
                                                    @endphp
                                                    <div class="avatar-sm me-3">
                                                        <div
                                                            class="avatar-title bg-{{ $roleColors[$role->name] ?? 'secondary' }}-subtle
                                        text-{{ $roleColors[$role->name] ?? 'secondary' }} rounded-circle">
                                                            <i class="lni lni-user"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $user->name }}</h6>
                                                        <small class="text-muted">{{ $user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @foreach ($user->roles as $role)
                                                    <span class="badge bg-{{ $roleColors[$role->name] ?? 'secondary' }}">
                                                        {{ ucfirst($role->name) }}
                                                    </span>
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
                                                @if ($user->email_verified_at)
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="lni lni-checkmark"></i> Sim
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning">
                                                        <i class="lni lni-warning"></i> Não
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $user->created_at->format('d/m/Y') }}</small>
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown">
                                                        <i class="lni lni-cog"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.users.show', $user) }}">
                                                                <i class="lni lni-eye me-2"></i> Ver Detalhes
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.users.edit', $user) }}">
                                                                <i class="lni lni-pencil me-2"></i> Editar
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        @if ($user->isPending())
                                                            <li>
                                                                <a class="dropdown-item text-success"
                                                                    href="{{ route('admin.users.approve', $user) }}"
                                                                    onclick="return confirm('Aprovar este usuário?')">
                                                                    <i class="lni lni-checkmark-circle me-2"></i> Aprovar
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item text-danger"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#rejectModal{{ $user->id }}">
                                                                    <i class="lni lni-cross-circle me-2"></i> Rejeitar
                                                                </button>
                                                            </li>
                                                        @elseif($user->isApproved())
                                                            <li>
                                                                <button class="dropdown-item text-warning"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#suspendModal{{ $user->id }}">
                                                                    <i class="lni lni-ban me-2"></i> Suspender
                                                                </button>
                                                            </li>
                                                        @elseif($user->isSuspended())
                                                            <li>
                                                                <a class="dropdown-item text-success"
                                                                    href="{{ route('admin.users.activate', $user) }}"
                                                                    onclick="return confirm('Ativar este usuário?')">
                                                                    <i class="lni lni-checkmark me-2"></i> Ativar
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <button class="dropdown-item text-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal{{ $user->id }}">
                                                                <i class="lni lni-trash me-2"></i> Remover
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal de Rejeição -->
                                        <div class="modal fade" id="rejectModal{{ $user->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.users.reject', $user) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Rejeitar Usuário</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Rejeitar <strong>{{ $user->name }}</strong>?</p>
                                                            <div class="mb-3">
                                                                <label class="form-label">Motivo da Rejeição</label>
                                                                <textarea class="form-control" name="reason" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Confirmar
                                                                Rejeição</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal de Suspensão -->
                                        <div class="modal fade" id="suspendModal{{ $user->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.users.suspend', $user) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Suspender Usuário</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Suspender <strong>{{ $user->name }}</strong>?</p>
                                                            <div class="mb-3">
                                                                <label class="form-label">Motivo da Suspensão</label>
                                                                <textarea class="form-control" name="reason" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Confirmar
                                                                Suspensão</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal de Remoção -->
                                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Remover Usuário</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Tem certeza que deseja remover
                                                                <strong>{{ $user->name }}</strong>?
                                                            </p>
                                                            <div class="alert alert-danger">
                                                                <i class="lni lni-warning me-2"></i>
                                                                Esta ação não pode ser desfeita. Todos os dados relacionados
                                                                serão
                                                                removidos.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Confirmar
                                                                Remoção</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="lni lni-users display-4"></i>
                                                        <p class="mt-2">Nenhum usuário encontrado</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginação -->
                            @if ($users->hasPages())
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-muted">
                                        Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de
                                        {{ $users->total() }}
                                        resultados
                                    </div>
                                    <div>
                                        {{ $users->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal de Filtros -->
                <div class="modal fade" id="filterModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.users.index') }}" method="GET">
                                <div class="modal-header">
                                    <h5 class="modal-title">Filtrar Usuários</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Buscar</label>
                                            <input type="text" class="form-control" name="search"
                                                value="{{ request('search') }}" placeholder="Nome, email ou telefone...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="status">
                                                <option value="">Todos</option>
                                                <option value="pending"
                                                    {{ request('status') == 'pending' ? 'selected' : '' }}>
                                                    Pendentes</option>
                                                <option value="approved"
                                                    {{ request('status') == 'approved' ? 'selected' : '' }}>
                                                    Aprovados</option>
                                                <option value="rejected"
                                                    {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                                    Rejeitados</option>
                                                <option value="suspended"
                                                    {{ request('status') == 'suspended' ? 'selected' : '' }}>
                                                    Suspensos</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Perfil</label>
                                            <select class="form-select" name="role">
                                                <option value="">Todos</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}"
                                                        {{ request('role') == $role->name ? 'selected' : '' }}>
                                                        {{ ucfirst($role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Email Verificado</label>
                                            <select class="form-select" name="verified">
                                                <option value="">Todos</option>
                                                <option value="yes" {{ request('verified') == 'yes' ? 'selected' : '' }}>
                                                    Sim
                                                </option>
                                                <option value="no" {{ request('verified') == 'no' ? 'selected' : '' }}>Não
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Limpar</a>
                                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                        @push('scripts')
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Controle de seleção
                                    const selectAll = document.getElementById('selectAll');
                                    const selectAllTable = document.getElementById('selectAllTable');
                                    const checkboxes = document.querySelectorAll('.user-checkbox');
                                    const selectedCount = document.getElementById('selectedCount');
                                    const bulkAction = document.getElementById('bulkAction');
                                    const reasonField = document.getElementById('reasonField');
                                    const applyBulkAction = document.getElementById('applyBulkAction');

                                    function updateSelectedCount() {
                                        const selected = document.querySelectorAll('.user-checkbox:checked').length;
                                        selectedCount.textContent = `${selected} selecionado(s)`;
                                    }

                                    function toggleReasonField() {
                                        if (['reject', 'suspend'].includes(bulkAction.value)) {
                                            reasonField.classList.remove('d-none');
                                            reasonField.querySelector('textarea').required = true;
                                        } else {
                                            reasonField.classList.add('d-none');
                                            reasonField.querySelector('textarea').required = false;
                                        }
                                    }

                                    // Selecionar todos
                                    selectAll?.addEventListener('change', function() {
                                        checkboxes.forEach(cb => cb.checked = this.checked);
                                        updateSelectedCount();
                                    });

                                    selectAllTable?.addEventListener('change', function() {
                                        checkboxes.forEach(cb => cb.checked = this.checked);
                                        updateSelectedCount();
                                    });

                                    // Atualizar contagem
                                    checkboxes.forEach(cb => {
                                        cb.addEventListener('change', updateSelectedCount);
                                    });

                                    // Mostrar/ocultar campo de motivo
                                    bulkAction?.addEventListener('change', toggleReasonField);

                                    // Aplicar ação em massa
                                    applyBulkAction?.addEventListener('click', function() {
                                        const selected = document.querySelectorAll('.user-checkbox:checked');
                                        if (selected.length === 0) {
                                            alert('Selecione pelo menos um usuário.');
                                            return;
                                        }

                                        if (!bulkAction.value) {
                                            alert('Selecione uma ação.');
                                            return;
                                        }

                                        if (confirm(`Deseja ${bulkAction.value} ${selected.length} usuário(s)?`)) {
                                            document.getElementById('bulkForm').submit();
                                        }
                                    });

                                    // Inicializar
                                    updateSelectedCount();
                                    toggleReasonField();
                                });
                            </script>
                        @endpush
                    @endsection
