@extends('layouts.app')
@section('title', 'Detalhes do Aluno')
@section('cssjs')
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
@endsection
@section('content')
    <div class="admin-wrapper">
        @include('admin.partials.sidebar')
        <!-- Conteúdo Principal -->
        <main class="admin-main">
            <!-- Header -->
            @include('admin.partials.header')

            <div class="container-fluid px-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Alunos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
                </ol>
                <div class="row">
                    <!-- Coluna da Esquerda: Informações -->
                    <div class="col-xl-8">
                        <!-- Informações Gerais -->
                        <div class="card ip-card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="lni lni-user me-2"></i>
                                    Informações Gerais
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 text-center mb-4">
                                        <div class="avatar-xl mx-auto mb-3">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                <i class="lni lni-graduation display-4"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary mb-1">Aluno</span>
                                            <div class="mt-2">
                                                @switch($student->status)
                                                    @case('active')
                                                        <span class="badge bg-success">Ativo</span>
                                                    @break

                                                    @case('inactive')
                                                        <span class="badge bg-warning">Inativo</span>
                                                    @break

                                                    @case('graduated')
                                                        <span class="badge bg-info">Formado</span>
                                                    @break

                                                    @case('transferred')
                                                        <span class="badge bg-secondary">Transferido</span>
                                                    @break
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Nome Completo</label>
                                                <p class="mb-0">{{ $student->user->name }}</p>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Email</label>
                                                <p class="mb-0">
                                                    <a href="mailto:{{ $student->user->email }}"
                                                        class="text-decoration-none">
                                                        {{ $student->user->email }}
                                                    </a>
                                                    @if ($student->user->email_verified_at)
                                                        <span class="badge bg-success-subtle text-success ms-1">
                                                            <i class="lni lni-checkmark"></i> Verificado
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Telefone</label>
                                                <p class="mb-0">{{ $student->user->phone ?? 'Não informado' }}</p>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Data de Nascimento</label>
                                                <p class="mb-0">
                                                    {{ $student->user->birth_date?->format('d/m/Y') ?? 'Não informada' }}
                                                    @if ($student->user->birth_date)
                                                        <small class="text-muted ms-2">
                                                            ({{ \Carbon\Carbon::parse($student->user->birth_date)->age }}
                                                            anos)
                                                        </small>
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Gênero</label>
                                                <p class="mb-0">
                                                    {{ [
                                                        'male' => 'Masculino',
                                                        'female' => 'Feminino',
                                                        'other' => 'Outro',
                                                        'prefer_not_to_say' => 'Prefiro não dizer',
                                                    ][$student->user->gender] ?? 'Não informado' }}
                                                </p>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Endereço</label>
                                                <p class="mb-0">{{ $student->user->address ?? 'Não informado' }}</p>
                                            </div>

                                            @if ($student->user->emergency_contact)
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small mb-1">Contato de
                                                        Emergência</label>
                                                    <p class="mb-0">{{ $student->user->emergency_contact }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informações Acadêmicas -->
                        <div class="card ip-card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="lni lni-graduation me-2"></i>
                                    Informações Acadêmicas
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small mb-1">Matrícula</label>
                                        <p class="mb-0">
                                            <span class="badge bg-secondary">{{ $student->registration_number }}</span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small mb-1">Documento de Identificação</label>
                                        <p class="mb-0">{{ $student->identity_document ?? 'Não informado' }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small mb-1">Turma</label>
                                        <p class="mb-0">
                                            @if ($student->turma)
                                                <a href="{{ route('admin.turmas.show', $student->turma) }}"
                                                    class="text-decoration-none">
                                                    <span class="badge bg-primary">{{ $student->turma->name }}</span>
                                                </a>
                                            @else
                                                <span class="text-muted">Não atribuída</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small mb-1">Data de Matrícula</label>
                                        <p class="mb-0">
                                            {{ $student->enrollment_date }}
                                            <small class="text-muted ms-2">
                                                ({{ $student->enrollment_date }})
                                            </small>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small mb-1">Status</label>
                                        <p class="mb-0">
                                            @switch($student->status)
                                                @case('active')
                                                    <span class="badge bg-success">Ativo</span>
                                                @break

                                                @case('inactive')
                                                    <span class="badge bg-warning">Inativo</span>
                                                @break

                                                @case('graduated')
                                                    <span class="badge bg-info">Formado</span>
                                                @break

                                                @case('transferred')
                                                    <span class="badge bg-secondary">Transferido</span>
                                                @break
                                            @endswitch
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small mb-1">Data de Registro</label>
                                        <p class="mb-0">
                                            {{ $student->created_at->format('d/m/Y H:i') }}
                                            <small class="text-muted ms-2">
                                                ({{ $student->created_at->diffForHumans() }})
                                            </small>
                                        </p>
                                    </div>

                                    @if ($student->notes)
                                        <div class="col-12">
                                            <label class="form-label text-muted small mb-1">Observações</label>
                                            <div class="alert alert-light">
                                                {{ $student->notes }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Coluna da Direita: Ações e Responsáveis -->
                    <div class="col-xl-4">
                        <!-- Ações -->
                        <div class="card ip-card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="lni lni-cog me-2"></i>
                                    Ações
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary">
                                        <i class="lni lni-pencil me-1"></i>
                                        Editar Aluno
                                    </a>

                                    @if ($student->status === 'active')
                                        <form action="{{ route('admin.students.update', $student) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="inactive">
                                            <button type="submit" class="btn btn-warning w-100"
                                                onclick="return confirm('Inativar este aluno?')">
                                                <i class="lni lni-ban me-1"></i>
                                                Inativar Aluno
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.students.update', $student) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="btn btn-success w-100"
                                                onclick="return confirm('Ativar este aluno?')">
                                                <i class="lni lni-checkmark me-1"></i>
                                                Ativar Aluno
                                            </button>
                                        </form>
                                    @endif

                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                        <i class="lni lni-trash me-1"></i>
                                        Remover Aluno
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Responsáveis -->
                        <div class="card ip-card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="lni lni-user me-2"></i>
                                    Responsáveis
                                </h6>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#addGuardianModal">
                                    <i class="lni lni-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                @if ($student->guardians->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach ($student->guardians as $guardian)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $guardian->user->name }}</h6>
                                                    <small class="text-muted">
                                                        {{ $guardian->relationship ?? 'Responsável' }}
                                                        @if ($guardian->user->phone)
                                                            · {{ $guardian->user->phone }}
                                                        @endif
                                                    </small>
                                                </div>
                                                <form
                                                    action="{{ route('admin.students.guardian.detach', [$student, $guardian]) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Remover este responsável?')">
                                                        <i class="lni lni-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="lni lni-user display-4 text-muted"></i>
                                        <p class="text-muted mt-2">Nenhum responsável vinculado</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Contato -->
                        <div class="card ip-card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="lni lni-phone me-2"></i>
                                    Contato
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="mailto:{{ $student->user->email }}" class="btn btn-outline-primary">
                                        <i class="lni lni-envelope me-1"></i>
                                        Enviar Email
                                    </a>
                                    @if ($student->user->phone)
                                        <a href="tel:{{ $student->user->phone }}" class="btn btn-outline-success">
                                            <i class="lni lni-phone me-1"></i>
                                            Ligar para {{ $student->user->phone }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Remoção -->
                <div class="modal fade" id="deleteModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.students.destroy', $student) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title">Remover Aluno</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Tem certeza que deseja remover <strong>{{ $student->user->name }}</strong>?</p>
                                    <div class="alert alert-danger">
                                        <i class="lni lni-warning me-2"></i>
                                        Esta ação não pode ser desfeita. Todos os dados relacionados serão removidos.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger">Confirmar Remoção</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal para adicionar responsável -->
                <div class="modal fade" id="addGuardianModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.students.guardian.attach', $student) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Vincular Responsável</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Selecione o responsável</label>
                                        <select class="form-select" name="guardian_id" required>
                                            <option value="">Selecione...</option>
                                            @foreach ($guardians as $guardian)
                                                <option value="{{ $guardian->id }}">
                                                    {{ $guardian->user->name }}
                                                    @if ($guardian->relationship)
                                                        ({{ $guardian->relationship }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Parentesco/Relacionamento</label>
                                        <input type="text" class="form-control" name="relationship"
                                            placeholder="Ex: Pai, Mãe, Avó...">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Vincular</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        function copyEmail() {
            navigator.clipboard.writeText("{{ $student->user->email }}").then(() => {
                alert('Email copiado para a área de transferência!');
            });
        }
    </script>
@endpush
