<!-- resources/views/livewire/admin/users.blade.php -->
@section('page-title', 'Gerenciar Usuários')
@section('page-subtitle', 'Administre todos os usuários do sistema')

<div>
    <div class="fade-in">
        <!-- Estatísticas -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card-stat border-primary">
                    <div class="card-stat-icon bg-primary-light">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-stat-value">{{ $stats['total'] }}</div>
                    <div class="card-stat-label">Total de Usuários</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat border-success">
                    <div class="card-stat-icon bg-success-light">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="card-stat-value">{{ $stats['active'] }}</div>
                    <div class="card-stat-label">Usuários Ativos</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat border-warning">
                    <div class="card-stat-icon bg-warning-light">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="card-stat-value">{{ $stats['pending'] }}</div>
                    <div class="card-stat-label">Aguardando Aprovação</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat border-danger">
                    <div class="card-stat-icon bg-danger-light">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <div class="card-stat-value">{{ $stats['inactive'] }}</div>
                    <div class="card-stat-label">Usuários Inativos</div>
                </div>
            </div>
        </div>

        <!-- Filtros e Ações -->
        <div class="card border-none shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Buscar usuário..."
                            wire:model.live.debounce.300ms="search">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" wire:model.live="role">
                            <option value="">Todos os Tipos</option>
                            @foreach ($roles as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" wire:model.live="status">
                            <option value="">Todos os Status</option>
                            <option value="active">Ativos</option>
                            <option value="inactive">Inativos</option>
                            <option value="pending">Pendentes</option>
                            <option value="approved">Aprovados</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" wire:model.live="sortField">
                            <option value="created_at">Data de Criação</option>
                            <option value="name">Nome</option>
                            <option value="email">Email</option>
                            <option value="last_login_at">Último Login</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex justify-content-end">
                        <button class="btn btn-primary" wire:click="$set('showForm', true)">
                            <i class="fas fa-plus me-2"></i> Novo Usuário
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Usuários -->
        <div class="card table-card">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Lista de Usuários</h6>
                <small class="text-muted">{{ $users->total() }} usuários encontrados</small>
            </div>
            <div class="card-body">
                @if ($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                                        Nome
                                        @if ($sortField === 'name')
                                            <i
                                                class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </th>
                                    <th>Email/Telefone</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th wire:click="sortBy('last_login_at')" style="cursor: pointer;">
                                        Último Login
                                        @if ($sortField === 'last_login_at')
                                            <i
                                                class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <img src="{{ $user->full_photo_url }}" class="rounded-circle" width="40"
                                                height="40" alt="{{ $user->name }}"
                                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0055ff&color=fff'">
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $user->name }}</div>
                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $user->email }}</div>
                                            <small class="text-muted">{{ $user->phone }}</small>
                                        </td>
                                        <td>
                                            <span
                                                class="badge @switch($user->role)
                                    @case('admin') bg-danger @break
                                    @case('secretary') bg-warning @break
                                    @case('teacher') bg-info @break
                                    @case('student') bg-primary @break
                                    @default bg-secondary
                                @endswitch">
                                                {{ $roles[$user->role] ?? $user->role }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                                    {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                                </span>
                                                <span
                                                    class="badge bg-{{ $user->is_approved ? 'success' : 'warning' }}">
                                                    {{ $user->is_approved ? 'Aprovado' : 'Pendente' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($user->last_login_at)
                                                <span class="text-success">
                                                    <i class="fas fa-circle text-success me-1"
                                                        style="font-size: 8px;"></i>
                                                    {{ $user->last_login_at->diffForHumans() }}
                                                </span>
                                            @else
                                                <span class="text-muted">Nunca logou</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary"
                                                    wire:click="editUser({{ $user->id }})" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                @if (!$user->is_approved)
                                                    <button class="btn btn-outline-success"
                                                        wire:click="approveUser({{ $user->id }})"
                                                        wire:confirm="Aprovar este usuário?" title="Aprovar">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif

                                                <button
                                                    class="btn btn-outline-{{ $user->is_active ? 'warning' : 'success' }}"
                                                    wire:click="toggleStatus({{ $user->id }})"
                                                    wire:confirm="{{ $user->is_active ? 'Desativar' : 'Ativar' }} este usuário?"
                                                    title="{{ $user->is_active ? 'Desativar' : 'Ativar' }}">
                                                    <i
                                                        class="fas fa-{{ $user->is_active ? 'ban' : 'check-circle' }}"></i>
                                                </button>

                                                @if ($user->id !== auth()->id())
                                                    <button class="btn btn-outline-danger"
                                                        wire:click="confirmDelete({{ $user->id }})"
                                                        title="Excluir">
                                                        <i class="fas fa-trash"></i>
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
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Mostrando {{ $users->firstItem() }} - {{ $users->lastItem() }} de {{ $users->total() }}
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhum usuário encontrado</h5>
                        <p class="text-muted">Nenhum usuário corresponde aos filtros selecionados.</p>
                        <button class="btn btn-primary" wire:click="$set('showForm', true)">
                            <i class="fas fa-plus me-2"></i> Criar Primeiro Usuário
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de Formulário -->
    @if ($showForm)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editMode ? 'Editar Usuário' : 'Novo Usuário' }}</h5>
                        <button type="button" class="btn-close" wire:click="resetForm"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="createUser">
                            <div class="row">
                                <!-- Foto de Perfil -->
                                <div class="col-md-3 text-center mb-4">
                                    <div class="position-relative d-inline-block">
                                        <img src="{{ $photo ? $photo->temporaryUrl() : 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=0055ff&color=fff' }}"
                                            class="rounded-circle mb-3" width="150" height="150"
                                            alt="Foto de perfil">

                                        <div class="mt-2">
                                            <label for="photoUpload" class="btn btn-sm btn-primary mb-1">
                                                <i class="fas fa-camera me-1"></i> Alterar Foto
                                            </label>
                                            <input type="file" id="photoUpload" class="d-none" wire:model="photo"
                                                accept="image/*">
                                        </div>

                                        @error('photo')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Dados do Usuário -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nome Completo *</label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                wire:model="name" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email *</label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                wire:model="email" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Telefone *</label>
                                            <input type="text"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                wire:model="phone" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tipo de Usuário *</label>
                                            <select class="form-select @error('roleForm') is-invalid @enderror"
                                                wire:model="roleForm" required>
                                                @foreach ($roles as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('roleForm')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">
                                                {{ $editMode ? 'Nova Senha' : 'Senha' }}
                                                @if ($editMode)
                                                    <small class="text-muted">(Deixe em branco para manter a
                                                        atual)</small>
                                                @endif
                                            </label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                wire:model="password" {{ !$editMode ? 'required' : '' }}>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Confirmar Senha</label>
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                wire:model="password_confirmation" {{ !$editMode ? 'required' : '' }}>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_active"
                                                    wire:model="is_active" {{ $is_active ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Usuário Ativo
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_approved"
                                                    wire:model="is_approved" {{ $is_approved ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_approved">
                                                    Conta Aprovada
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-secondary" wire:click="resetForm">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ $editMode ? 'Atualizar' : 'Criar' }} Usuário
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Confirmação de Exclusão -->
    @if ($showDeleteModal && $userToDelete)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">Confirmar Exclusão</h5>
                        <button type="button" class="btn-close" wire:click="closeDeleteModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                            <h5 class="fw-bold">Tem certeza que deseja excluir este usuário?</h5>
                        </div>

                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Atenção:</strong> Esta ação não pode ser desfeita. Todos os dados do usuário serão
                            permanentemente removidos.
                        </div>

                        <div class="card border-danger mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold">Informações do Usuário:</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Nome:</strong> {{ $userToDelete->name }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Email:</strong> {{ $userToDelete->email }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Tipo:</strong> {{ $roles[$userToDelete->role] ?? $userToDelete->role }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Registro:</strong> {{ $userToDelete->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmDelete">
                            <label class="form-check-label" for="confirmDelete">
                                Eu entendo que esta ação é irreversível e concordo com a exclusão.
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDeleteModal">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="deleteUser"
                            onclick="return document.getElementById('confirmDelete').checked;">
                            <i class="fas fa-trash me-2"></i> Excluir Permanentemente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @section('js')
        <script>
            // Preview da foto
            document.addEventListener('livewire:init', () => {
                Livewire.on('photo-updated', () => {
                    // Recarregar a página para mostrar a nova foto
                    window.location.reload();
                });
            });

            // Ordenação por coluna
            function sortTable(field) {
                Livewire.dispatch('sortBy', {
                    field: field
                });
            }

            // Confirmação de ações
            function confirmAction(action, userId) {
                if (confirm(`Tem certeza que deseja ${action} este usuário?`)) {
                    Livewire.dispatch(action, {
                        userId: userId
                    });
                }
            }
        </script>
    @endsection
</div>
