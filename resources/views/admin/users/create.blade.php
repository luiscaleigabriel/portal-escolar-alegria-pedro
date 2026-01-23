@extends('layouts.app')
@section('title', 'Novo Usuário')
@section('cssjs')
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
@endsection
@section('content')
    <div class="admin-wrapper">
        @include('admin.partials.sidebar')
        <!-- Conteúdo Principal -->
        <main class="admin-main">
            <!-- Cabeçalho -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Novo Usuário</li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-0 text-gray-800">Novo Usuário</h1>
                </div>
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="lni lni-arrow-left me-1"></i>
                        Voltar
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card ip-card">
                        <div class="card-body">
                            <form action="{{ route('admin.users.store') }}" method="POST" id="userForm">
                                @csrf

                                <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="basic-tab" data-bs-toggle="tab"
                                            data-bs-target="#basic" type="button">
                                            <i class="lni lni-user me-2"></i>
                                            Informações Básicas
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#profile" type="button">
                                            <i class="lni lni-cog me-2"></i>
                                            Perfil Específico
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="access-tab" data-bs-toggle="tab"
                                            data-bs-target="#access" type="button">
                                            <i class="lni lni-lock me-2"></i>
                                            Acesso
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="userTabsContent">
                                    <!-- Aba 1: Informações Básicas -->
                                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Nome Completo *</label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    value="{{ old('name') }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Email *</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email') }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Telefone</label>
                                                <input type="text"
                                                    class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                    value="{{ old('phone') }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Data de Nascimento</label>
                                                <input type="date"
                                                    class="form-control @error('birth_date') is-invalid @enderror"
                                                    name="birth_date" value="{{ old('birth_date') }}">
                                                @error('birth_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Gênero</label>
                                                <select class="form-select @error('gender') is-invalid @enderror"
                                                    name="gender">
                                                    <option value="">Selecione...</option>
                                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                        Masculino</option>
                                                    <option value="female"
                                                        {{ old('gender') == 'female' ? 'selected' : '' }}>Feminino</option>
                                                    <option value="other"
                                                        {{ old('gender') == 'other' ? 'selected' : '' }}>Outro</option>
                                                    <option value="prefer_not_to_say"
                                                        {{ old('gender') == 'prefer_not_to_say' ? 'selected' : '' }}>
                                                        Prefiro não dizer</option>
                                                </select>
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Endereço</label>
                                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2">{{ old('address') }}</textarea>
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Contato de Emergência</label>
                                                <input type="text"
                                                    class="form-control @error('emergency_contact') is-invalid @enderror"
                                                    name="emergency_contact" value="{{ old('emergency_contact') }}">
                                                @error('emergency_contact')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Aba 2: Perfil Específico -->
                                    <div class="tab-pane fade" id="profile" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Perfil *</label>
                                                <select class="form-select @error('role') is-invalid @enderror"
                                                    name="role" id="roleSelect" required>
                                                    <option value="">Selecione o perfil...</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->name }}"
                                                            {{ old('role') == $role->name ? 'selected' : '' }}>
                                                            {{ ucfirst($role->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('role')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Status *</label>
                                                <select class="form-select @error('status') is-invalid @enderror"
                                                    name="status" required>
                                                    <option value="pending"
                                                        {{ old('status') == 'pending' ? 'selected' : '' }}>Pendente
                                                    </option>
                                                    <option value="approved"
                                                        {{ old('status') == 'approved' ? 'selected' : '' }}>Aprovado
                                                    </option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Campos específicos para Estudante -->
                                            <div id="studentFields" class="row g-3 d-none">
                                                <div class="col-md-6">
                                                    <label class="form-label">Número de Matrícula</label>
                                                    <input type="text" class="form-control" name="registration_number"
                                                        value="{{ old('registration_number') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Documento de Identificação</label>
                                                    <input type="text" class="form-control" name="identity_document"
                                                        value="{{ old('identity_document') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Turma</label>
                                                    <select class="form-select" name="turma_id">
                                                        <option value="">Selecione uma turma...</option>
                                                        @foreach ($turmas as $turma)
                                                            <option value="{{ $turma->id }}"
                                                                {{ old('turma_id') == $turma->id ? 'selected' : '' }}>
                                                                {{ $turma->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Campos específicos para Responsável -->
                                            <div id="guardianFields" class="row g-3 d-none">
                                                <div class="col-12">
                                                    <label class="form-label">Alunos Vinculados</label>
                                                    <select class="form-select" name="student_ids[]" multiple
                                                        style="height: 150px;">
                                                        <!-- Os alunos serão carregados via AJAX se necessário -->
                                                        <option value="">Nenhum aluno selecionado</option>
                                                    </select>
                                                    <div class="form-text">
                                                        Selecione os alunos que este responsável irá acompanhar.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Aba 3: Acesso -->
                                    <div class="tab-pane fade" id="access" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Senha *</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Confirmar Senha *</label>
                                                <input type="password"
                                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                                    name="password_confirmation" required>
                                            </div>

                                            <div class="col-12">
                                                <div class="alert alert-info">
                                                    <i class="lni lni-information me-2"></i>
                                                    Um email de boas-vindas será enviado ao usuário com as instruções de
                                                    acesso.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="window.history.back()">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="lni lni-checkmark-circle me-1"></i>
                                        Salvar Usuário
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const roleSelect = document.getElementById('roleSelect');
                const studentFields = document.getElementById('studentFields');
                const guardianFields = document.getElementById('guardianFields');

                function toggleProfileFields() {
                    const role = roleSelect.value;

                    // Oculta todos os campos específicos
                    studentFields.classList.add('d-none');
                    guardianFields.classList.add('d-none');

                    // Mostra campos específicos baseados no role
                    if (role === 'student') {
                        studentFields.classList.remove('d-none');
                    } else if (role === 'guardian') {
                        guardianFields.classList.remove('d-none');
                    }
                }

                roleSelect.addEventListener('change', toggleProfileFields);

                // Inicializar campos baseados no valor atual (útil para validação)
                toggleProfileFields();

                // Validação do formulário
                const form = document.getElementById('userForm');
                form.addEventListener('submit', function(e) {
                    let isValid = true;

                    // Validações adicionais podem ser adicionadas aqui

                    if (!isValid) {
                        e.preventDefault();
                    }
                });
            });
        </script>
    @endpush
@endsection
