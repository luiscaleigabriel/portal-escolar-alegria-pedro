<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Portal Escolar Alegria Pedro</title>

    <!-- CSS via Vite -->
    @vite(['resources/css/auth.css'])

    <!-- Ícones LineIcons -->
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">

    <style>
        /* Estilos específicos para registro */
        .registration-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .registration-steps::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #E5E7EB;
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #E5E7EB;
            color: #6B7280;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .step.active .step-circle {
            background: var(--ip-primary-blue);
            color: white;
        }

        .step.completed .step-circle {
            background: var(--ip-success-color);
            color: white;
        }

        .step-label {
            font-size: 0.75rem;
            color: #6B7280;
            font-weight: 500;
        }

        .step.active .step-label {
            color: var(--ip-primary-blue);
            font-weight: 600;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeIn 0.5s ease-out;
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 6px;
            background: #E5E7EB;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 0.25rem;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .strength-text {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .role-card {
            border: 2px solid #E5E7EB;
            border-radius: var(--bs-border-radius-lg);
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            height: 100%;
        }

        .role-card:hover {
            border-color: var(--ip-accent-blue);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .role-card.active {
            border-color: var(--ip-primary-blue);
            background: rgba(30, 58, 138, 0.05);
        }

        .role-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            display: block;
        }

        .role-card.student .role-icon {
            color: var(--ip-primary-blue);
        }

        .role-card.teacher .role-icon {
            color: var(--ip-secondary-blue);
        }

        .role-card.guardian .role-icon {
            color: var(--ip-success-color);
        }

        .terms-checkbox .form-check-input:checked {
            background-color: var(--ip-primary-blue);
            border-color: var(--ip-primary-blue);
        }

        .terms-link {
            color: var(--ip-primary-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .terms-link:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .role-fields {
            display: none;
            animation: fadeIn 0.3s ease-out;
        }

        .role-fields.show {
            display: block;
        }

        /* Estilo para campos obrigatórios */
        .required-field::after {
            content: " *";
            color: var(--ip-error-color);
        }

        /* Estilo para idade calculada */
        .age-display {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        .age-display.valid {
            color: var(--ip-success-color);
        }

        .age-display.invalid {
            color: var(--ip-error-color);
        }
    </style>
</head>
<body class="auth-page">
    <!-- Loading inicial -->
    <div class="loading" id="loading">
        <div class="spinner"></div>
        <span>Carregando formulário...</span>
    </div>

    <!-- Conteúdo principal -->
    <div id="authContent" style="display: none;">
        <!-- Background pattern -->
        <div class="auth-bg-pattern"></div>

        <div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
            <div class="auth-card-container animate-fade-in" style="width: 100%; max-width: 520px;">
                <div class="auth-card shadow-lg">
                    <!-- Cabeçalho -->
                    <div class="auth-header">
                        <div class="auth-logo">
                            <div class="logo-circle">
                                <i class="lni lni-graduation logo-icon"></i>
                            </div>
                            <h1 class="h2 text-white mb-2">Criar Conta</h1>
                            <p class="text-white-50 mb-0">
                                <span class="text-gradient-gold fw-bold">Portal Alegria Pedro</span>
                            </p>
                        </div>
                    </div>

                    <!-- Corpo -->
                    <div class="p-4 p-md-5">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading mb-2">Por favor, corrija os seguintes erros:</h6>
                                @foreach ($errors->all() as $error)
                                    <div class="mb-1">• {{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Passos do registro -->
                        <div class="registration-steps mb-4">
                            <div class="step active" id="step1">
                                <div class="step-circle">1</div>
                                <div class="step-label">Dados Pessoais</div>
                            </div>
                            <div class="step" id="step2">
                                <div class="step-circle">2</div>
                                <div class="step-label">Tipo de Conta</div>
                            </div>
                            <div class="step" id="step3">
                                <div class="step-circle">3</div>
                                <div class="step-label">Confirmação</div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf

                            <!-- Passo 1: Dados Pessoais -->
                            <div class="form-step active" id="step1Form">
                                <h4 class="mb-4">Dados Pessoais</h4>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-12">
                                        <label for="name" class="form-label required-field">
                                            <i class="lni lni-user"></i>
                                            Nome Completo
                                        </label>
                                        <input type="text" id="name" name="name"
                                            class="form-control auth-form-control" value="{{ old('name') }}" required
                                            placeholder="João da Silva Santos">
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label required-field">
                                            <i class="lni lni-envelope"></i>
                                            Email
                                        </label>
                                        <input type="email" id="email" name="email"
                                            class="form-control auth-form-control" value="{{ old('email') }}" required
                                            placeholder="seu@email.com">
                                        <div class="form-text">Usaremos este email para comunicação</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label required-field">
                                            <i class="lni lni-phone"></i>
                                            Telefone
                                        </label>
                                        <input type="tel" id="phone" name="phone"
                                            class="form-control auth-form-control" value="{{ old('phone') }}"
                                            required placeholder="+244 900 000 000">
                                        <div class="form-text">Com código do país</div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="birth_date" class="form-label required-field">
                                            <i class="lni lni-calendar"></i>
                                            Data de Nascimento
                                        </label>
                                        <input type="date" id="birth_date" name="birth_date"
                                            class="form-control auth-form-control" value="{{ old('birth_date') }}"
                                            required onchange="calculateAge()">
                                        <div id="ageDisplay" class="age-display"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="lni lni-gender"></i>
                                            Gênero
                                        </label>
                                        <select class="form-control auth-form-control" name="gender">
                                            <option value="">Selecione...</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculino</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Feminino</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Outro</option>
                                            <option value="prefer_not_to_say" {{ old('gender') == 'prefer_not_to_say' ? 'selected' : '' }}>Prefiro não dizer</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="address" class="form-label required-field">
                                        <i class="lni lni-map-marker"></i>
                                        Endereço Completo
                                    </label>
                                    <textarea id="address" name="address" class="form-control auth-form-control" rows="3" required
                                        placeholder="Rua, número, bairro, cidade, província">{{ old('address') }}</textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                        <i class="lni lni-arrow-left"></i>
                                        Voltar para Login
                                    </a>
                                    <button type="button" class="btn btn-ip-primary" onclick="validateAndNext(2)">
                                        Próximo
                                        <i class="lni lni-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Passo 2: Tipo de Conta e Credenciais -->
                            <div class="form-step" id="step2Form">
                                <h4 class="mb-4">Tipo de Conta e Credenciais</h4>

                                <!-- Seleção de Perfil -->
                                <div class="mb-4">
                                    <label class="form-label required-field mb-3">
                                        <i class="lni lni-briefcase"></i>
                                        Selecione o tipo de conta:
                                    </label>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="role-card student" onclick="selectRole('student')"
                                                id="roleStudent">
                                                <i class="lni lni-graduation role-icon"></i>
                                                <h5 class="mb-2">Aluno</h5>
                                                <p class="text-muted small">Estudante matriculado</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="role-card teacher" onclick="selectRole('teacher')"
                                                id="roleTeacher">
                                                <i class="lni lni-users role-icon"></i>
                                                <h5 class="mb-2">Professor</h5>
                                                <p class="text-muted small">Docente do instituto</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="role-card guardian" onclick="selectRole('guardian')"
                                                id="roleGuardian">
                                                <i class="lni lni-user role-icon"></i>
                                                <h5 class="mb-2">Responsável</h5>
                                                <p class="text-muted small">Pai/mãe ou responsável</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos específicos por role -->
                                <div class="role-fields mb-4" id="studentFields">
                                    <h6 class="mb-3">
                                        <i class="lni lni-graduation"></i>
                                        Informações do Aluno
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="identity_document" class="form-label required-field">
                                                <i class="lni lni-id-card"></i>
                                                Documento de Identificação
                                            </label>
                                            <input type="text" id="identity_document" name="identity_document"
                                                class="form-control auth-form-control"
                                                value="{{ old('identity_document') }}"
                                                placeholder="BI/Passaporte">
                                            <div class="form-text">Número do documento oficial</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="registration_number" class="form-label">
                                                <i class="lni lni-agenda"></i>
                                                Número de Matrícula
                                            </label>
                                            <input type="text" id="registration_number" name="registration_number"
                                                class="form-control auth-form-control"
                                                value="{{ old('registration_number') }}"
                                                placeholder="Se já tiver matrícula">
                                            <div class="form-text">Opcional para novos alunos</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="role-fields mb-4" id="teacherFields">
                                    <h6 class="mb-3">
                                        <i class="lni lni-certificate"></i>
                                        Informações do Professor
                                    </h6>
                                    <div class="alert alert-info">
                                        <i class="lni lni-information"></i>
                                        Sua conta como professor será validada pela administração.
                                        Você receberá um email quando for aprovado.
                                    </div>
                                </div>

                                <div class="role-fields mb-4" id="guardianFields">
                                    <h6 class="mb-3">
                                        <i class="lni lni-users"></i>
                                        Informações do Responsável
                                    </h6>
                                    <div class="mb-3">
                                        <label for="emergency_contact" class="form-label">
                                            <i class="lni lni-phone"></i>
                                            Telefone de Emergência
                                        </label>
                                        <input type="tel" id="emergency_contact" name="emergency_contact"
                                            class="form-control auth-form-control"
                                            value="{{ old('emergency_contact') }}"
                                            placeholder="+244 900 000 000">
                                        <div class="form-text">Para contato em caso de emergência</div>
                                    </div>
                                    <div class="alert alert-warning">
                                        <i class="lni lni-warning"></i>
                                        Após o registro, você precisará ser vinculado a um aluno pela administração.
                                    </div>
                                </div>

                                <!-- Senha -->
                                <div class="mb-4">
                                    <h6 class="mb-3">
                                        <i class="lni lni-lock-alt"></i>
                                        Crie sua Senha
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="password" class="form-label required-field">
                                                Senha
                                            </label>
                                            <input type="password" id="password" name="password"
                                                class="form-control auth-form-control" required placeholder="••••••••"
                                                oninput="checkPasswordStrength(this.value)">
                                            <div class="password-strength">
                                                <div class="strength-bar">
                                                    <div class="strength-fill" id="strengthFill"></div>
                                                </div>
                                                <span class="strength-text" id="strengthText">Força da senha</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="password_confirmation" class="form-label required-field">
                                                Confirmar Senha
                                            </label>
                                            <input type="password" id="password_confirmation" name="password_confirmation"
                                                class="form-control auth-form-control" required placeholder="••••••••"
                                                oninput="checkPasswordMatch()">
                                            <div class="form-text" id="passwordMatchText"></div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="role" name="role" value="">

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">
                                        <i class="lni lni-arrow-left"></i>
                                        Voltar
                                    </button>
                                    <button type="button" class="btn btn-ip-primary" onclick="validateAndNext(3)"
                                        id="nextStep2Btn" disabled>
                                        Próximo
                                        <i class="lni lni-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Passo 3: Confirmação -->
                            <div class="form-step" id="step3Form">
                                <h4 class="mb-4">Confirmação Final</h4>

                                <div class="card ip-card mb-4">
                                    <div class="card-body">
                                        <h6 class="card-title">Resumo da sua Conta</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">Nome:</small>
                                                <p id="summaryName" class="mb-2">-</p>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Email:</small>
                                                <p id="summaryEmail" class="mb-2">-</p>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Telefone:</small>
                                                <p id="summaryPhone" class="mb-2">-</p>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Data Nasc.:</small>
                                                <p id="summaryBirthDate" class="mb-2">-</p>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Tipo de Conta:</small>
                                                <p id="summaryRole" class="mb-2">-</p>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Data de Registo:</small>
                                                <p id="summaryDate" class="mb-2">{{ date('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                        <div id="summaryExtra" class="mt-3 pt-3 border-top"></div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check terms-checkbox">
                                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                        <label class="form-check-label" for="terms">
                                            Concordo com os
                                            <a href="#" class="terms-link" onclick="showTermsModal()">Termos de Uso</a>
                                            e
                                            <a href="#" class="terms-link" onclick="showPrivacyModal()">Política de Privacidade</a>
                                            do Instituto Polícia
                                        </label>
                                    </div>
                                </div>

                                <div class="alert alert-warning">
                                    <div class="d-flex">
                                        <i class="lni lni-timer mt-1 me-3"></i>
                                        <div>
                                            <strong>Atenção:</strong>
                                            <p class="mb-0 small">
                                                Sua conta será criada com status <strong>"Pendente"</strong> e
                                                precisará ser aprovada pela administração. Você receberá um email
                                                quando sua conta for ativada.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                                        <i class="lni lni-arrow-left"></i>
                                        Voltar
                                    </button>
                                    <button type="submit" class="btn btn-ip-primary" id="submitBtn">
                                        <i class="lni lni-checkmark-circle"></i>
                                        Confirmar Registo
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                Já tem uma conta?
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                    Entre aqui
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Termos (simplificado) -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Termos de Uso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Conteúdo dos termos de uso...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Variáveis globais
        let currentStep = 1;
        let selectedRole = '';

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('authContent').style.display = 'block';

                // Restaurar dados antigos
                restoreFormData();

                // Calcular idade se houver data
                calculateAge();
            }, 500);

            // Handler do formulário
            setupFormSubmit();
        });

        // Restaurar dados do formulário
        function restoreFormData() {
            const oldRole = "{{ old('role') }}";
            if (oldRole) {
                selectRole(oldRole);
            }

            // Preencher summary com dados existentes
            updateSummary();
        }

        // Configurar submit do formulário
        function setupFormSubmit() {
            const registerForm = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');

            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    if (!validateFinalStep()) {
                        e.preventDefault();
                        return;
                    }

                    const originalHTML = submitBtn.innerHTML;
                    submitBtn.innerHTML = `
                        <div class="auth-loader"></div>
                        <span>Processando registo...</span>
                    `;
                    submitBtn.disabled = true;

                    setTimeout(() => {
                        submitBtn.innerHTML = originalHTML;
                        submitBtn.disabled = false;
                    }, 10000);
                });
            }
        }

        // Navegação entre passos
        function validateAndNext(step) {
            if (currentStep === 1 && !validateStep1()) return;
            if (currentStep === 2 && !validateStep2()) return;

            if (step === 3) {
                updateSummary();
            }

            goToStep(step);
        }

        function goToStep(step) {
            document.getElementById(`step${currentStep}Form`).classList.remove('active');
            document.getElementById(`step${currentStep}`).classList.remove('active');

            document.getElementById(`step${step}Form`).classList.add('active');
            document.getElementById(`step${step}`).classList.add('active');

            if (currentStep < step) {
                document.getElementById(`step${currentStep}`).classList.add('completed');
            } else {
                document.getElementById(`step${currentStep}`).classList.remove('completed');
            }

            currentStep = step;
        }

        function prevStep(step) {
            goToStep(step);
        }

        // Validações
        function validateStep1() {
            const fields = ['name', 'email', 'phone', 'birth_date', 'address'];
            const labels = ['nome', 'email', 'telefone', 'data de nascimento', 'endereço'];

            for (let i = 0; i < fields.length; i++) {
                const field = document.getElementById(fields[i]);
                if (field && !field.value.trim()) {
                    alert(`Por favor, insira seu ${labels[i]}.`);
                    field.focus();
                    return false;
                }
            }

            // Validar email
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Por favor, insira um email válido.');
                return false;
            }

            // Validar idade
            const birthDate = document.getElementById('birth_date').value;
            if (birthDate) {
                const age = calculateAgeFromDate(birthDate);
                if (age < 6) {
                    alert('Você deve ter pelo menos 6 anos para se registar.');
                    return false;
                }
                if (age > 120) {
                    alert('Por favor, verifique sua data de nascimento.');
                    return false;
                }
            }

            return true;
        }

        function validateStep2() {
            if (!selectedRole) {
                alert('Por favor, selecione o tipo de conta.');
                return false;
            }

            // Validar campos específicos do aluno
            if (selectedRole === 'student') {
                const identityDoc = document.getElementById('identity_document');
                if (identityDoc && !identityDoc.value.trim()) {
                    alert('Por favor, insira o documento de identificação.');
                    identityDoc.focus();
                    return false;
                }
            }

            // Validar senha
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (password.length < 8) {
                alert('A senha deve ter pelo menos 8 caracteres.');
                return false;
            }

            if (password !== confirmPassword) {
                alert('As senhas não coincidem.');
                return false;
            }

            return true;
        }

        function validateFinalStep() {
            if (!document.getElementById('terms').checked) {
                alert('Por favor, aceite os termos de uso e política de privacidade.');
                return false;
            }

            return true;
        }

        // Cálculo de idade
        function calculateAge() {
            const birthDate = document.getElementById('birth_date').value;
            const ageDisplay = document.getElementById('ageDisplay');

            if (!birthDate) {
                if (ageDisplay) ageDisplay.textContent = '';
                return;
            }

            const age = calculateAgeFromDate(birthDate);
            const today = new Date(birthDate);
            const formattedDate = today.toLocaleDateString('pt-PT');

            if (ageDisplay) {
                ageDisplay.textContent = `${formattedDate} (${age} anos)`;
                ageDisplay.className = 'age-display ' + (age >= 6 ? 'valid' : 'invalid');

                if (age < 6) {
                    ageDisplay.innerHTML += ' <small class="text-danger">(Mínimo: 6 anos)</small>';
                }
            }

            return age;
        }

        function calculateAgeFromDate(birthDate) {
            const birth = new Date(birthDate);
            const today = new Date();
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age--;
            }

            return age;
        }

        // Seleção de role
        function selectRole(role) {
            selectedRole = role;
            document.getElementById('role').value = role;

            // Atualizar UI
            updateRoleUI(role);
            updateSummary();
        }

        function updateRoleUI(role) {
            // Atualizar cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('active');
            });
            document.getElementById(`role${role.charAt(0).toUpperCase() + role.slice(1)}`).classList.add('active');

            // Mostrar campos específicos
            document.querySelectorAll('.role-fields').forEach(field => {
                field.classList.remove('show');
            });
            document.getElementById(`${role}Fields`).classList.add('show');

            // Habilitar botão próximo
            document.getElementById('nextStep2Btn').disabled = false;

            // Atualizar texto do botão
            const nextBtn = document.getElementById('nextStep2Btn');
            const roleNames = {
                'student': 'Aluno',
                'teacher': 'Professor',
                'guardian': 'Responsável'
            };
            nextBtn.innerHTML = `Continuar como ${roleNames[role]} <i class="lni lni-arrow-right"></i>`;
        }

        // Atualizar resumo
        function updateSummary() {
            const data = {
                name: getValue('name'),
                email: getValue('email'),
                phone: getValue('phone'),
                birthDate: getValue('birth_date'),
                role: selectedRole,
                identityDoc: selectedRole === 'student' ? getValue('identity_document') : null,
                registrationNumber: selectedRole === 'student' ? getValue('registration_number') : null,
                emergencyContact: selectedRole === 'guardian' ? getValue('emergency_contact') : null,
                address: getValue('address'),
                gender: getValue('gender')
            };

            // Atualizar campos básicos
            document.getElementById('summaryName').textContent = data.name || '-';
            document.getElementById('summaryEmail').textContent = data.email || '-';
            document.getElementById('summaryPhone').textContent = data.phone || '-';

            if (data.birthDate) {
                const date = new Date(data.birthDate);
                const age = calculateAgeFromDate(data.birthDate);
                document.getElementById('summaryBirthDate').textContent =
                    `${date.toLocaleDateString('pt-PT')} (${age} anos)`;
            } else {
                document.getElementById('summaryBirthDate').textContent = '-';
            }

            document.getElementById('summaryRole').textContent = getRoleName(selectedRole) || '-';
            document.getElementById('summaryDate').textContent = new Date().toLocaleDateString('pt-PT');

            // Atualizar informações extras
            updateExtraSummary(data);
        }

        function updateExtraSummary(data) {
            const extraDiv = document.getElementById('summaryExtra');
            if (!extraDiv) return;

            let html = '<h6 class="text-muted mb-2">Informações Adicionais</h6><div class="row">';

            if (data.address) {
                html += `<div class="col-12 mb-2"><small class="text-muted">Endereço:</small><p class="mb-0 small">${data.address}</p></div>`;
            }

            if (data.gender) {
                const genderText = {
                    'male': 'Masculino',
                    'female': 'Feminino',
                    'other': 'Outro',
                    'prefer_not_to_say': 'Prefiro não dizer'
                }[data.gender] || data.gender;
                html += `<div class="col-6 mb-2"><small class="text-muted">Gênero:</small><p class="mb-0">${genderText}</p></div>`;
            }

            if (data.identityDoc) {
                html += `<div class="col-6 mb-2"><small class="text-muted">Documento:</small><p class="mb-0">${data.identityDoc}</p></div>`;
            }

            if (data.registrationNumber) {
                html += `<div class="col-6 mb-2"><small class="text-muted">Matrícula:</small><p class="mb-0">${data.registrationNumber}</p></div>`;
            }

            if (data.emergencyContact) {
                html += `<div class="col-6 mb-2"><small class="text-muted">Emergência:</small><p class="mb-0">${data.emergencyContact}</p></div>`;
            }

            html += '</div>';
            extraDiv.innerHTML = html;
        }

        function getValue(id) {
            const element = document.getElementById(id);
            return element ? element.value : '';
        }

        function getRoleName(role) {
            const roles = {
                'student': 'Aluno',
                'teacher': 'Professor',
                'guardian': 'Responsável'
            };
            return roles[role] || '';
        }

        // Validação de senha
        function checkPasswordStrength(password) {
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;
            let color = '#EF4444';
            let text = 'Fraca';

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            switch (strength) {
                case 0:
                case 1:
                    color = '#EF4444';
                    text = 'Fraca';
                    break;
                case 2:
                    color = '#F59E0B';
                    text = 'Média';
                    break;
                case 3:
                    color = '#10B981';
                    text = 'Forte';
                    break;
                case 4:
                    color = '#059669';
                    text = 'Muito Forte';
                    break;
            }

            const width = (strength / 4) * 100;
            if (strengthFill) strengthFill.style.width = width + '%';
            if (strengthFill) strengthFill.style.backgroundColor = color;
            if (strengthText) strengthText.textContent = text;
            if (strengthText) strengthText.style.color = color;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchText = document.getElementById('passwordMatchText');

            if (confirmPassword) {
                if (password === confirmPassword) {
                    matchText.textContent = 'As senhas coincidem ✓';
                    matchText.style.color = '#10B981';
                } else {
                    matchText.textContent = 'As senhas não coincidem ✗';
                    matchText.style.color = '#EF4444';
                }
            } else {
                matchText.textContent = '';
            }
        }

        // Modais
        function showTermsModal() {
            const modal = new bootstrap.Modal(document.getElementById('termsModal'));
            modal.show();
        }

        function showPrivacyModal() {
            // Similar ao showTermsModal
            alert('Modal de Política de Privacidade');
        }
    </script>

    <!-- Bootstrap JS para modais -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Vite JS -->
    @vite(['resources/js/app.js'])
</body>
</html>
