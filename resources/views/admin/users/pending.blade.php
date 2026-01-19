@extends('layouts.admin')

@section('title', 'Usuários Pendentes')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="lni lni-timer"></i>
                Usuários Pendentes
            </h1>
            <p class="text-muted">Aprove ou rejeite novos registros</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.users.stats') }}" class="btn btn-outline-primary">
                <i class="lni lni-stats-up"></i>
                Estatísticas
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            @if($pendingUsers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuário</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>Telefone</th>
                            <th>Registro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUsers as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->birth_date)
                                        <div class="text-muted small">
                                            {{ $user->birth_date->age }} anos
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->hasRole('student'))
                                <span class="badge bg-primary">
                                    <i class="lni lni-graduation"></i> Aluno
                                </span>
                                @elseif($user->hasRole('teacher'))
                                <span class="badge bg-success">
                                    <i class="lni lni-users"></i> Professor
                                </span>
                                @elseif($user->hasRole('guardian'))
                                <span class="badge bg-info">
                                    <i class="lni lni-user"></i> Responsável
                                </span>
                                @endif
                            </td>
                            <td>{{ $user->phone ?? 'Não informado' }}</td>
                            <td>
                                <span class="text-muted">{{ $user->created_at->diffForHumans() }}</span>
                                <div class="small">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Ver detalhes">
                                        <i class="lni lni-eye"></i>
                                    </a>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#approveModal{{ $user->id }}"
                                            title="Aprovar">
                                        <i class="lni lni-checkmark-circle"></i>
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $user->id }}"
                                            title="Rejeitar">
                                        <i class="lni lni-cross-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Aprovar -->
                        <div class="modal fade" id="approveModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Aprovar Usuário</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Você está prestes a aprovar o usuário:</p>
                                            <div class="alert alert-info">
                                                <strong>{{ $user->name }}</strong><br>
                                                <small>{{ $user->email }}</small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="notes{{ $user->id }}" class="form-label">
                                                    Observações (opcional)
                                                </label>
                                                <textarea class="form-control"
                                                          id="notes{{ $user->id }}"
                                                          name="notes"
                                                          rows="3"
                                                          placeholder="Ex: Conta aprovada para acesso ao sistema..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="lni lni-checkmark-circle"></i>
                                                Confirmar Aprovação
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Rejeitar -->
                        <div class="modal fade" id="rejectModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Rejeitar Usuário</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.users.reject', $user) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Você está prestes a rejeitar o usuário:</p>
                                            <div class="alert alert-warning">
                                                <strong>{{ $user->name }}</strong><br>
                                                <small>{{ $user->email }}</small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="reason{{ $user->id }}" class="form-label">
                                                    Motivo da Rejeição *
                                                </label>
                                                <textarea class="form-control"
                                                          id="reason{{ $user->id }}"
                                                          name="reason"
                                                          rows="4"
                                                          placeholder="Ex: Documentação incompleta, informações inconsistentes..."
                                                          required></textarea>
                                                <div class="form-text">
                                                    Este motivo será enviado ao usuário por email.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="lni lni-cross-circle"></i>
                                                Confirmar Rejeição
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $pendingUsers->links() }}
            </div>

            @else
            <div class="text-center py-5">
                <i class="lni lni-checkmark-circle" style="font-size: 4rem; color: #10B981;"></i>
                <h4 class="mt-3">Nenhum usuário pendente</h4>
                <p class="text-muted">Todos os registros foram processados.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--ip-primary-blue) 0%, var(--ip-secondary-blue) 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.1rem;
}
</style>
@endsection
