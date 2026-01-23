@extends('layouts.app')
@section('title', 'Editar Usuário')
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
                                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Editar</li>
                            </ol>
                        </nav>
                        <h1 class="h3 mb-0 text-gray-800">Editar Usuário</h1>
                    </div>
                    <div>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">
                            <i class="lni lni-arrow-left me-1"></i>
                            Voltar
                        </a>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="card ip-card">
                            <div class="card-body">
                                <form action="{{ route('admin.users.update', $user) }}" method="POST" id="userForm">
                                    @csrf
                                    @method('PUT')

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
                                    </ul>

                                    <div class="tab-content" id="userTabsContent">
                                        <!-- Aba 1: Informações Básicas -->
                                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nome Completo *</label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name', $user->name) }}" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Email *</label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email', $user->email) }}" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Telefone</label>
                                                    <input type="text"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        name="phone" value="{{ old('phone', $user->phone) }}">
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Data de Nascimento</label>
                                                    <input type="date"
                                                        class="form-control @error('birth_date') is-invalid @enderror"
                                                        name="birth_date"
                                                        value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}">
                                                    @error('birth_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Gênero</label>
                                                    <select class="form-select @error('gender') is-invalid @enderror"
                                                        name="gender">
                                                        <option value="">Selecione...</option>
                                                        <option value="male"
                                                            {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>
                                                            Masculino</option>
                                                        <option value="female"
                                                            {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>
                                                            Feminino</option>
                                                        <option value="other"
                                                            {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>
                                                            Outro</option>
                                                        <option value="prefer_not_to_say"
                                                            {{ old('gender', $user->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>
                                                            Prefiro não dizer</option>
                                                    </select>
                                                    @error('gender')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Status *</label>
                                                    <select class="form-select @error('status') is-invalid @enderror"
                                                        name="status" required>
                                                        <option value="pending"
                                                            {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>
                                                            Pendente</option>
                                                        <option value="approved"
                                                            {{ old('status', $user->status) == 'approved' ? 'selected' : '' }}>
                                                            Aprovado</option>
                                                        <option value="rejected"
                                                            {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>
                                                            Rejeitado</option>
                                                        <option value="suspended"
                                                            {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>
                                                            Suspenso</option>
                                                    </select>
                                                    @error('status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label">Endereço</label>
                                                    <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Contato de Emergência</label>
                                                    <input type="text"
                                                        class="form-control @error('emergency_contact') is-invalid @enderror"
                                                        name="emergency_contact"
                                                        value="{{ old('emergency_contact', $user->emergency_contact) }}">
                                                    @error('emergency_contact')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Perfil Atual</label>
                                                    <div class="form-control-plaintext">
                                                        @foreach ($user->roles as $role)
                                                            <span
                                                                class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Aba 2: Perfil Específico -->
                                        <div class="tab-pane fade" id="profile" role="tabpanel">
                                            @if ($user->hasRole('student'))
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Número de Matrícula</label>
                                                        <input type="text" class="form-control"
                                                            name="registration_number"
                                                            value="{{ old('registration_number', $profile->registration_number ?? '') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Documento de Identificação</label>
                                                        <input type="text" class="form-control"
                                                            name="identity_document"
                                                            value="{{ old('identity_document', $profile->identity_document ?? '') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Turma</label>
                                                        <select class="form-select" name="turma_id">
                                                            <option value="">Selecione uma turma...</option>
                                                            @foreach ($turmas as $turma)
                                                                <option value="{{ $turma->id }}"
                                                                    {{ old('turma_id', $profile->turma_id ?? '') == $turma->id ? 'selected' : '' }}>
                                                                    {{ $turma->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($user->hasRole('guardian'))
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label class="form-label">Alunos Vinculados</label>
                                                        <select class="form-select" name="student_ids[]" multiple
                                                            style="height: 200px;">
                                                            @foreach ($students as $student)
                                                                <option value="{{ $student->id }}"
                                                                    {{ old('student_ids', $profile->students->pluck('id')->toArray() ?? [])
                                                                        ? (in_array($student->id, old('student_ids', $profile->students->pluck('id')->toArray() ?? []))
                                                                            ? 'selected'
                                                                            : '')
                                                                        : '' }}>
                                                                    {{ $student->user->name }}
                                                                    @if ($student->registration_number)
                                                                        (Matrícula: {{ $student->registration_number }})
                                                                    @endif
                                                                    @if ($student->turma)
                                                                        - {{ $student->turma->name }}
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-text">
                                                            Segure Ctrl (ou Cmd no Mac) para selecionar múltiplos alunos.
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-info">
                                                    <i class="lni lni-information me-2"></i>
                                                    Não há campos específicos para edição deste tipo de perfil.
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="window.history.back()">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="lni lni-checkmark-circle me-1"></i>
                                            Atualizar Usuário
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Validação do status quando for rejeitado
                const statusSelect = document.querySelector('select[name="status"]');
                const userForm = document.getElementById('userForm');

                userForm.addEventListener('submit', function(e) {
                    if (statusSelect.value === 'rejected' || statusSelect.value === 'suspended') {
                        if (!confirm('Alterar o status para "' + statusSelect.options[statusSelect
                                    .selectedIndex].text +
                                '"? Esta ação pode afetar o acesso do usuário ao sistema.')) {
                            e.preventDefault();
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
