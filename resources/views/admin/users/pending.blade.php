@extends('layouts.app')

@section('title', 'Usuários Pendentes')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Usuários Pendentes</h1>
            <p class="mb-0 text-muted">Gerencie solicitações de registro no sistema</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="lni lni-arrow-left me-1"></i>
                Voltar
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                <i class="lni lni-users me-1"></i>
                Ver Todos
            </a>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Pendentes</h6>
                            <h3 class="mb-0">{{ $pendingUsers->total() }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                <i class="lni lni-timer"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Estudantes</h6>
                            <h3 class="mb-0">{{ $pendingUsers->where(fn($u) => $u->hasRole('student'))->count() }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="lni lni-graduation"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Professores</h6>
                            <h3 class="mb-0">{{ $pendingUsers->where(fn($u) => $u->hasRole('teacher'))->count() }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                <i class="lni lni-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Responsáveis</h6>
                            <h3 class="mb-0">{{ $pendingUsers->where(fn($u) => $u->hasRole('guardian'))->count() }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                <i class="lni lni-user"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Usuários -->
    <div class="card ip-card">
        <div class="card-header">
            <h6 class="mb-0">Lista de Usuários Pendentes</h6>
        </div>
        <div class="card-body">
            @if($pendingUsers->isEmpty())
            <div class="text-center py-5">
                <div class="avatar-lg mx-auto mb-3">
                    <div class="avatar-title bg-success-subtle text-success rounded-circle">
                        <i class="lni lni-checkmark-circle display-4"></i>
                    </div>
                </div>
                <h5 class="mb-2">Nenhum usuário pendente!</h5>
                <p class="text-muted">Todas as solicitações foram processadas.</p>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary mt-3">
                    Ver Todos os Usuários
                </a>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="40">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th>Usuário</th>
                            <th>Informações</th>
                            <th>Perfil</th>
                            <th>Data Registro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUsers as $user)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input select-item" type="checkbox" value="{{ $user->id }}">
                                </div>
                            </td>
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
                                    {{ $user->birth_date?->format('d/m/Y') ?? 'Não informada' }}
                                </small>
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                <span class="badge bg-{{ [
                                    'student' => 'primary',
                                    'teacher' => 'warning',
                                    'guardian' => 'info'
                                ][$role->name] ?? 'secondary' }}">
                                    {{ ucfirst($role->name) }}
                                </span>
                                @endforeach
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
                        Mostrando {{ $pendingUsers->firstItem() }} a {{ $pendingUsers->lastItem() }}
                        de {{ $pendingUsers->total() }} registros
                    </span>
                </div>
                <div>
                    {{ $pendingUsers->links() }}
                </div>
            </div>
            @endif
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

        // Seleção em lote
        const selectAll = document.getElementById('selectAll');
        const selectItems = document.querySelectorAll('.select-item');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                const isChecked = this.checked;
                selectItems.forEach(item => {
                    item.checked = isChecked;
                });
            });

            // Verificar se todos estão selecionados
            selectItems.forEach(item => {
                item.addEventListener('change', function() {
                    const allChecked = Array.from(selectItems).every(item => item.checked);
                    selectAll.checked = allChecked;
                });
            });
        }
    });
</script>
@endpush
@endsection
