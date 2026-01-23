@extends('layouts.app')
@section('title', 'Gestão de Alunos')
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
                <!-- Cabeçalho -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">Gestão de Alunos</h1>
                        <p class="text-muted mb-0">Gerencie todos os alunos do sistema</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                            <i class="lni lni-plus me-1"></i>
                            Novo Aluno
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
                                <li><a class="dropdown-item" href="{{ route('admin.students.export') }}?format=csv">CSV</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.students.export') }}?format=excel">Excel</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('admin.students.export') }}?format=pdf">PDF</a>
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
                                        <h6 class="text-muted fw-normal">Total de Alunos</h6>
                                        <h3 class="mb-0">{{ $students->total() }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="lni lni-users display-4 text-primary"></i>
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
                                        <h6 class="text-muted fw-normal">Ativos</h6>
                                        <h3 class="mb-0">{{ $students->where('status', 'active')->count() }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="lni lni-checkmark-circle display-4 text-success"></i>
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
                                        <h6 class="text-muted fw-normal">Inativos</h6>
                                        <h3 class="mb-0">{{ $students->where('status', 'inactive')->count() }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="lni lni-ban display-4 text-warning"></i>
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
                                        <h6 class="text-muted fw-normal">Formados</h6>
                                        <h3 class="mb-0">{{ $students->where('status', 'graduated')->count() }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="lni lni-graduation display-4 text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabela de Alunos -->
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
                                        <th>Aluno</th>
                                        <th>Matrícula</th>
                                        <th>Turma</th>
                                        <th>Status</th>
                                        <th>Data de Matrícula</th>
                                        <th class="text-end">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input student-checkbox" type="checkbox"
                                                        name="students[]" value="{{ $student->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <div
                                                            class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                            <i class="lni lni-user"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $student->user->name }}</h6>
                                                        <small class="text-muted">{{ $student->user->email }}</small>
                                                        @if ($student->user->phone)
                                                            <small
                                                                class="text-muted d-block">{{ $student->user->phone }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $student->registration_number }}</span>
                                            </td>
                                            <td>
                                                @if ($student->turma)
                                                    <span class="badge bg-primary">{{ $student->turma->name }}</span>
                                                @else
                                                    <span class="badge bg-warning">Sem turma</span>
                                                @endif
                                            </td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <small>{{ $student->enrollment_date }}</small>
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
                                                                href="{{ route('admin.students.show', $student) }}">
                                                                <i class="lni lni-eye me-2"></i> Ver Detalhes
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.students.edit', $student) }}">
                                                                <i class="lni lni-pencil me-2"></i> Editar
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        @if ($student->status === 'active')
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.students.update', $student) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status"
                                                                        value="inactive">
                                                                    <button type="submit"
                                                                        class="dropdown-item text-warning">
                                                                        <i class="lni lni-ban me-2"></i> Inativar
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.students.update', $student) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="active">
                                                                    <button type="submit"
                                                                        class="dropdown-item text-success">
                                                                        <i class="lni lni-checkmark me-2"></i> Ativar
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <button class="dropdown-item text-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal{{ $student->id }}">
                                                                <i class="lni lni-trash me-2"></i> Remover
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal de Remoção -->
                                        <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.students.destroy', $student) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Remover Aluno</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Tem certeza que deseja remover
                                                                <strong>{{ $student->user->name }}</strong>?
                                                            </p>
                                                            <div class="alert alert-danger">
                                                                <i class="lni lni-warning me-2"></i>
                                                                Esta ação não pode ser desfeita. Todos os dados relacionados
                                                                serão removidos.
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
                                                        <p class="mt-2">Nenhum aluno encontrado</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Paginação -->
                            @if ($students->hasPages())
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-muted">
                                        Mostrando {{ $students->firstItem() }} a {{ $students->lastItem() }} de
                                        {{ $students->total() }} resultados
                                    </div>
                                    <div>
                                        {{ $students->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Modal de Filtros -->
                    <div class="modal fade" id="filterModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.students.index') }}" method="GET">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Filtrar Alunos</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Buscar</label>
                                                <input type="text" class="form-control" name="search"
                                                    value="{{ request('search') }}"
                                                    placeholder="Nome, email ou matrícula...">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Status</label>
                                                <select class="form-select" name="status">
                                                    <option value="">Todos</option>
                                                    <option value="active"
                                                        {{ request('status') == 'active' ? 'selected' : '' }}>
                                                        Ativos</option>
                                                    <option value="inactive"
                                                        {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos
                                                    </option>
                                                    <option value="graduated"
                                                        {{ request('status') == 'graduated' ? 'selected' : '' }}>Formados
                                                    </option>
                                                    <option value="transferred"
                                                        {{ request('status') == 'transferred' ? 'selected' : '' }}>Transferidos
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Turma</label>
                                                <select class="form-select" name="turma_id">
                                                    <option value="">Todas</option>
                                                    @foreach ($turmas as $turma)
                                                        <option value="{{ $turma->id }}"
                                                            {{ request('turma_id') == $turma->id ? 'selected' : '' }}>
                                                            {{ $turma->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ route('admin.students.index') }}"
                                            class="btn btn-outline-secondary">Limpar</a>
                                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
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
            document.addEventListener('DOMContentLoaded', function() {
                // Controle de seleção
                const selectAll = document.getElementById('selectAll');
                const selectAllTable = document.getElementById('selectAllTable');
                const checkboxes = document.querySelectorAll('.student-checkbox');
                const selectedCount = document.getElementById('selectedCount');
                const bulkAction = document.getElementById('bulkAction');
                const turmaField = document.getElementById('turmaField');
                const applyBulkAction = document.getElementById('applyBulkAction');

                function updateSelectedCount() {
                    const selected = document.querySelectorAll('.student-checkbox:checked').length;
                    selectedCount.textContent = `${selected} selecionado(s)`;
                }

                function toggleTurmaField() {
                    if (bulkAction.value === 'change_turma') {
                        turmaField.classList.remove('d-none');
                        turmaField.querySelector('select').required = true;
                    } else {
                        turmaField.classList.add('d-none');
                        turmaField.querySelector('select').required = false;
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

                // Mostrar/ocultar campo de turma
                bulkAction?.addEventListener('change', toggleTurmaField);

                // Aplicar ação em massa
                applyBulkAction?.addEventListener('click', function() {
                    const selected = document.querySelectorAll('.student-checkbox:checked');
                    if (selected.length === 0) {
                        alert('Selecione pelo menos um aluno.');
                        return;
                    }

                    if (!bulkAction.value) {
                        alert('Selecione uma ação.');
                        return;
                    }

                    if (bulkAction.value === 'change_turma' && !document.querySelector(
                            'select[name="turma_id"]').value) {
                        alert('Selecione uma turma.');
                        return;
                    }

                    if (confirm(`Deseja ${bulkAction.value} ${selected.length} aluno(s)?`)) {
                        document.getElementById('bulkForm').submit();
                    }
                });

                // Inicializar
                updateSelectedCount();
                toggleTurmaField();
            });
        </script>
    @endpush
