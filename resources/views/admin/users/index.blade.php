@extends('layouts.app')

@section('title', 'Todos os Usuários')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Todos os Usuários</h1>
            <p class="mb-0 text-muted">Gerencie todos os usuários do sistema</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="lni lni-arrow-left me-1"></i>
                Voltar
            </a>
            <a href="{{ route('admin.users.pending') }}" class="btn btn-warning">
                <i class="lni lni-timer me-1"></i>
                Ver Pendentes
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card ip-card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Nome, email ou telefone...">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprovado</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspenso</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="role" class="form-label">Perfil</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">Todos</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Aluno</option>
                        <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Professor</option>
                        <option value="guardian" {{ request('role') == 'guardian' ? 'selected' : '' }}>Responsável</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="director" {{ request('role') == 'director' ? 'selected' : '' }}>Diretor</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="lni lni-search-alt me-1"></i>
                            Filtrar
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="lni lni-reload"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Usuários -->
    <div class="card ip-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Lista de Usuários</h6>
            <span class="badge bg-primary">{{ $users->total() }} usuários</span>
        </div>
        <div class="card-body">
            @if($users->isEmpty())
            <div class="text-center py-5">
                <div class="avatar-lg mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle">
                        <i class="lni lni-users display-4"></i>
                    </div>
                </div>
                <h5 class="mb-2">Nenhum usuário encontrado</h5>
                <p class="text-muted">Tente ajustar os filtros de busca.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Informações</th>
                            <th>Perfil</th>
                            <th>Status</th>
                            <th>Registro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-light rounded-circle text-primary">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small class="d-block">
                                    <i class="lni lni-phone me-1"></i>
                                    {{ $user->phone ?? 'Não informado' }}
                                </small>
                                <small class="d-block mt-1">
                                    <i class="lni lni-calendar me-1"></i>
                                    {{ $user->created_at->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                <span class="badge bg-{{ [
                                    'student' => 'primary',
                                    'teacher' => 'warning',
                                    'guardian' => 'info',
                                    'admin' => 'danger',
                                    'director' => 'success'
                                ][$role->name] ?? 'secondary' }}">
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
                                    @default
                                        <span class="badge bg-light text-dark">Desconhecido</span>
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

                                    @if($user->status === 'approved')
                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            onclick="suspendUser({{ $user->id }}, '{{ $user->name }}')"
                                            title="Suspender"
                                            data-bs-toggle="tooltip">
                                        <i class="lni lni-ban"></i>
                                    </button>
                                    @endif
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
                        Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }}
                        de {{ $users->total() }} registros
                    </span>
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Suspensão -->
<div class="modal fade" id="suspendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="suspendForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Suspender Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Você está prestes a suspender o usuário <strong id="suspendUserName"></strong>.</p>
                    <div class="mb-3">
                        <label for="suspendReason" class="form-label">Motivo da Suspensão <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="suspendReason" name="reason" rows="4" required
                                  placeholder="Informe o motivo da suspensão..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="lni lni-warning me-2"></i>
                        O usuário não poderá acessar o sistema enquanto estiver suspenso.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Suspensão</button>
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

        // Modal de suspensão
        const suspendModal = new bootstrap.Modal(document.getElementById('suspendModal'));

        window.suspendUser = function(userId, userName) {
            document.getElementById('suspendUserName').textContent = userName;
            document.getElementById('suspendForm').action = `/admin/users/${userId}/suspend`;
            suspendModal.show();
        };
    });
</script>
@endpush
@endsection
