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
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="lni lni-users me-1"></i>
                    Ver Todos
                </a>
                <a href="{{ route('admin.users.stats') }}" class="btn btn-outline-info">
                    <i class="lni lni-stats-up me-1"></i>
                    Estatísticas
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
                                <h3 class="mb-0">{{ $pendingUsers->where(fn($u) => $u->hasRole('student'))->count() }}
                                </h3>
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
                                <h3 class="mb-0">{{ $pendingUsers->where(fn($u) => $u->hasRole('teacher'))->count() }}
                                </h3>
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
                                <h3 class="mb-0">{{ $pendingUsers->where(fn($u) => $u->hasRole('guardian'))->count() }}
                                </h3>
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
                @if ($pendingUsers->isEmpty())
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
                                @foreach ($pendingUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input select-item" type="checkbox"
                                                    value="{{ $user->id }}">
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
                                            @foreach ($user->roles as $role)
                                                <span
                                                    class="badge bg-{{ [
                                                        'student' => 'primary',
                                                        'teacher' => 'warning',
                                                        'guardian' => 'info',
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
                                                    class="btn btn-outline-info" title="Ver Detalhes"
                                                    data-bs-toggle="tooltip">
                                                    <i class="lni lni-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-success approve-btn"
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}" title="Aprovar"
                                                    data-bs-toggle="tooltip">
                                                    <i class="lni lni-checkmark-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger reject-btn"
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}" title="Rejeitar"
                                                    data-bs-toggle="tooltip">
                                                    <i class="lni lni-cross-circle"></i>
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
                                Mostrando {{ $pendingUsers->firstItem() }} a {{ $pendingUsers->lastItem() }}
                                de {{ $pendingUsers->total() }} registros
                            </span>
                        </div>
                        <div>
                            {{ $pendingUsers->links() }}
                        </div>
                    </div>

                    <!-- Ações em Lote -->
                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex gap-2 align-items-center">
                                <select id="batchAction" class="form-select form-select-sm" style="width: auto;">
                                    <option value="">Ações em lote...</option>
                                    <option value="approve">Aprovar Selecionados</option>
                                    <option value="reject">Rejeitar Selecionados</option>
                                </select>
                                <button id="applyBatchAction" class="btn btn-sm btn-primary" disabled>
                                    Aplicar
                                </button>
                                <span id="selectedCount" class="text-muted small ms-2">0 selecionados</span>
                            </div>
                            <div>
                                <button id="selectAllBtn" class="btn btn-sm btn-outline-secondary">
                                    Selecionar Todos
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de Aprovação -->
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" id="approveForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Aprovar Usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Você está prestes a aprovar o usuário <strong id="approveUserName"></strong>.</p>
                        <div class="mb-3">
                            <label for="approveNotes" class="form-label">Observações (opcional)</label>
                            <textarea class="form-control" id="approveNotes" name="notes" rows="3"
                                placeholder="Adicione observações sobre esta aprovação..."></textarea>
                        </div>
                        <div class="alert alert-info">
                            <i class="lni lni-information me-2"></i>
                            Um email será enviado ao usuário informando sobre a aprovação.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Confirmar Aprovação</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Rejeição -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" id="rejectForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Rejeitar Usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Você está prestes a rejeitar o usuário <strong id="rejectUserName"></strong>.</p>
                        <div class="mb-3">
                            <label for="rejectReason" class="form-label">Motivo da Rejeição <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejectReason" name="reason" rows="4"
                                placeholder="Informe o motivo da rejeição..." required></textarea>
                            <div class="form-text">Este motivo será enviado por email ao usuário.</div>
                        </div>
                        <div class="alert alert-warning">
                            <i class="lni lni-warning me-2"></i>
                            Esta ação não pode ser desfeita.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Confirmar Rejeição</button>
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
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Modais
                const approveModal = new bootstrap.Modal(document.getElementById('approveModal'));
                const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));

                // Botões de aprovação
                document.querySelectorAll('.approve-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const userId = this.dataset.userId;
                        const userName = this.dataset.userName;

                        document.getElementById('approveUserName').textContent = userName;
                        document.getElementById('approveForm').action =
                        `/admin/users/${userId}/approve`;

                        approveModal.show();
                    });
                });

                // Botões de rejeição
                document.querySelectorAll('.reject-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const userId = this.dataset.userId;
                        const userName = this.dataset.userName;

                        document.getElementById('rejectUserName').textContent = userName;
                        document.getElementById('rejectForm').action = `/admin/users/${userId}/reject`;

                        rejectModal.show();
                    });
                });

                // Seleção em lote
                const selectAll = document.getElementById('selectAll');
                const selectItems = document.querySelectorAll('.select-item');
                const selectedCount = document.getElementById('selectedCount');
                const applyBatchAction = document.getElementById('applyBatchAction');
                const batchAction = document.getElementById('batchAction');
                const selectAllBtn = document.getElementById('selectAllBtn');

                // Selecionar todos
                selectAll?.addEventListener('change', function() {
                    const isChecked = this.checked;
                    selectItems.forEach(item => {
                        item.checked = isChecked;
                    });
                    updateSelectedCount();
                });

                selectAllBtn?.addEventListener('click', function() {
                    const allChecked = Array.from(selectItems).every(item => item.checked);
                    selectItems.forEach(item => {
                        item.checked = !allChecked;
                    });
                    selectAll.checked = !allChecked;
                    updateSelectedCount();
                });

                // Atualizar contador
                function updateSelectedCount() {
                    const checkedItems = Array.from(selectItems).filter(item => item.checked);
                    selectedCount.textContent = `${checkedItems.length} selecionados`;
                    applyBatchAction.disabled = checkedItems.length === 0 || !batchAction.value;
                }

                selectItems.forEach(item => {
                    item.addEventListener('change', updateSelectedCount);
                });

                batchAction?.addEventListener('change', function() {
                    applyBatchAction.disabled = !this.value ||
                        Array.from(selectItems).filter(item => item.checked).length === 0;
                });

                // Aplicar ação em lote
                applyBatchAction?.addEventListener('click', function() {
                    const action = batchAction.value;
                    const selectedIds = Array.from(selectItems)
                        .filter(item => item.checked)
                        .map(item => item.value);

                    if (!action || selectedIds.length === 0) return;

                    if (action === 'approve') {
                        if (confirm(`Aprovar ${selectedIds.length} usuário(s) selecionado(s)?`)) {
                            approveBatch(selectedIds);
                        }
                    } else if (action === 'reject') {
                        const reason = prompt(
                            `Informe o motivo para rejeitar ${selectedIds.length} usuário(s):`);
                        if (reason) {
                            rejectBatch(selectedIds, reason);
                        }
                    }
                });

                // Funções para ações em lote
                function approveBatch(ids) {
                    fetch('/admin/users/batch-approve', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                ids: ids
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                }

                function rejectBatch(ids, reason) {
                    fetch('/admin/users/batch-reject', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                ids: ids,
                                reason: reason
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                }
            });
        </script>
    @endpush
@endsection
