@extends('layouts.app')
@section('title', 'Editar Aluno')
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
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.students.show', $student) }}">{{ $student->user->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
                </ol>

                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="card ip-card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="lni lni-pencil me-2"></i>
                                    Editar Aluno
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.students.update', $student) }}" method="POST"
                                    id="studentForm">
                                    @csrf
                                    @method('PUT')

                                    <ul class="nav nav-tabs mb-4" id="studentTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab"
                                                data-bs-target="#basic" type="button">
                                                <i class="lni lni-user me-2"></i>
                                                Informações Pessoais
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="academic-tab" data-bs-toggle="tab"
                                                data-bs-target="#academic" type="button">
                                                <i class="lni lni-graduation me-2"></i>
                                                Dados Acadêmicos
                                            </button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="studentTabsContent">
                                        <!-- Aba 1: Informações Pessoais -->
                                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nome Completo *</label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name', $student->user->name) }}"
                                                        required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Email *</label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email', $student->user->email) }}"
                                                        required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Telefone</label>
                                                    <input type="text"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        name="phone" value="{{ old('phone', $student->user->phone) }}">
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Data de Nascimento</label>
                                                    <input type="date"
                                                        class="form-control @error('birth_date') is-invalid @enderror"
                                                        name="birth_date"
                                                        value="{{ old('birth_date', $student->user->birth_date?->format('Y-m-d')) }}">
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
                                                            {{ old('gender', $student->user->gender) == 'male' ? 'selected' : '' }}>
                                                            Masculino</option>
                                                        <option value="female"
                                                            {{ old('gender', $student->user->gender) == 'female' ? 'selected' : '' }}>
                                                            Feminino</option>
                                                        <option value="other"
                                                            {{ old('gender', $student->user->gender) == 'other' ? 'selected' : '' }}>
                                                            Outro</option>
                                                        <option value="prefer_not_to_say"
                                                            {{ old('gender', $student->user->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>
                                                            Prefiro não dizer</option>
                                                    </select>
                                                    @error('gender')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label">Endereço</label>
                                                    <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2">{{ old('address', $student->user->address) }}</textarea>
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Contato de Emergência</label>
                                                    <input type="text"
                                                        class="form-control @error('emergency_contact') is-invalid @enderror"
                                                        name="emergency_contact"
                                                        value="{{ old('emergency_contact', $student->user->emergency_contact) }}">
                                                    @error('emergency_contact')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Aba 2: Dados Acadêmicos -->
                                        <div class="tab-pane fade" id="academic" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Número de Matrícula *</label>
                                                    <input type="text"
                                                        class="form-control @error('registration_number') is-invalid @enderror"
                                                        name="registration_number"
                                                        value="{{ old('registration_number', $student->registration_number) }}"
                                                        required>
                                                    @error('registration_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Documento de Identificação</label>
                                                    <input type="text"
                                                        class="form-control @error('identity_document') is-invalid @enderror"
                                                        name="identity_document"
                                                        value="{{ old('identity_document', $student->identity_document) }}">
                                                    @error('identity_document')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Turma *</label>
                                                    <select class="form-select @error('turma_id') is-invalid @enderror"
                                                        name="turma_id" required>
                                                        <option value="">Selecione uma turma...</option>
                                                        @foreach ($turmas as $turma)
                                                            <option value="{{ $turma->id }}"
                                                                {{ old('turma_id', $student->turma_id) == $turma->id ? 'selected' : '' }}>
                                                                {{ $turma->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('turma_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Data de Matrícula *</label>
                                                    <input type="date"
                                                        class="form-control @error('enrollment_date') is-invalid @enderror"
                                                        name="enrollment_date"
                                                        value="{{ old('enrollment_date', $student->enrollment_date) }}"
                                                        required>
                                                    @error('enrollment_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Status *</label>
                                                    <select class="form-select @error('status') is-invalid @enderror"
                                                        name="status" required>
                                                        <option value="active"
                                                            {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>
                                                            Ativo</option>
                                                        <option value="inactive"
                                                            {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>
                                                            Inativo</option>
                                                        <option value="graduated"
                                                            {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>
                                                            Formado</option>
                                                        <option value="transferred"
                                                            {{ old('status', $student->status) == 'transferred' ? 'selected' : '' }}>
                                                            Transferido</option>
                                                    </select>
                                                    @error('status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label">Responsáveis</label>
                                                    <select class="form-select" name="guardian_ids[]" multiple
                                                        style="height: 150px;">
                                                        @foreach ($guardians as $guardian)
                                                            <option value="{{ $guardian->id }}"
                                                                {{ in_array($guardian->id, old('guardian_ids', $student->guardians->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                                {{ $guardian->user->name }}
                                                                @if ($guardian->relationship)
                                                                    ({{ $guardian->relationship }})
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="form-text">Segure Ctrl (ou Cmd no Mac) para selecionar
                                                        múltiplos responsáveis</div>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label">Observações</label>
                                                    <textarea class="form-control" name="notes" rows="3">{{ old('notes', $student->notes) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="{{ route('admin.students.show', $student) }}"
                                            class="btn btn-outline-secondary">
                                            <i class="lni lni-arrow-left me-1"></i>
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="lni lni-checkmark-circle me-1"></i>
                                            Atualizar Aluno
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

