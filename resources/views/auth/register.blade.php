<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Portal Escolar Instituto Polícia</title>

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
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .student-fields,
        .teacher-fields,
        .guardian-fields {
            display: none;
        }

        .student-fields.show,
        .teacher-fields.show,
        .guardian-fields.show {
            display: block;
            animation: fadeIn 0.3s ease-out;
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
            <div class="auth-card-container animate-fade-in" style="width: 100%; max-width: 500px;">
                <div class="auth-card shadow-lg">
                    <!-- Cabeçalho -->
                    <div class="auth-header">
                        <div class="auth-logo">
                            <div class="logo-circle">
                                <i class="lni lni-graduation logo-icon"></i>
                            </div>
                            <h1 class="h2 text-white mb-2">Criar Conta</h1>
                            <p class="text-white-50 mb-0">
                                <span class="text-gradient-gold fw-bold">INSTITUTO POLÍCIA</span>
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
                                <div class="step-label">Conta</div>
                            </div>
                            <div class="step" id="step2">
                                <div class="step-circle">2</div>
                                <div class="step-label">Perfil</div>
                            </div>
                            <div class="step" id="step3">
                                <div class="step-circle">3</div>
                                <div class="step-label">Confirmação</div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf

                            <!-- Passo 1: Informações da Conta -->
                            <div class="form-step active" id="step1Form">
                                <h4 class="mb-4">Informações da Conta</h4>

                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="lni lni-user"></i>
                                        Nome Completo
                                    </label>
                                    <input type="text" id="name" name="name"
                                        class="form-control auth-form-control" value="{{ old('name') }}" required
                                        placeholder="João da Silva">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="lni lni-envelope"></i>
                                        Email
                                    </label>
                                    <input type="email" id="email" name="email"
                                        class="form-control auth-form-control" value="{{ old('email') }}" required
                                        placeholder="seu@email.com">
                                    <div class="form-text">Usaremos este email para comunicação</div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">
                                            <i class="lni lni-lock-alt"></i>
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
                                        <label for="password_confirmation" class="form-label">
                                            <i class="lni lni-lock-alt"></i>
                                            Confirmar Senha
                                        </label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control auth-form-control" required placeholder="••••••••"
                                            oninput="checkPasswordMatch()">
                                        <div class="form-text" id="passwordMatchText"></div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                        <i class="lni lni-arrow-left"></i>
                                        Voltar para Login
                                    </a>
                                    <button type="button" class="btn btn-ip-primary" onclick="nextStep(2)">
                                        Próximo
                                        <i class="lni lni-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Passo 2: Seleção de Perfil -->
                            <div class="form-step" id="step2Form">
                                <h4 class="mb-4">Selecione seu Perfil</h4>
                                <p class="text-muted mb-4">Escolha o tipo de conta que melhor descreve você</p>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <div class="role-card student" onclick="selectRole('student')"
                                            id="roleStudent">
                                            <i class="lni lni-graduation role-icon"></i>
                                            <h5 class="mb-2">Aluno</h5>
                                            <p class="text-muted small">Estudante matriculado no instituto</p>
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
                                            <p class="text-muted small">Pai/mãe ou responsável legal</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos adicionais para todos os usuários -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">
                                            <i class="lni lni-phone"></i>
                                            Telefone *
                                        </label>
                                        <input type="tel" id="phone" name="phone"
                                            class="form-control auth-form-control" value="{{ old('phone') }}"
                                            required placeholder="+244 900 000 000">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="birth_date" class="form-label">
                                            <i class="lni lni-calendar"></i>
                                            Data de Nascimento *
                                        </label>
                                        <input type="date" id="birth_date" name="birth_date"
                                            class="form-control auth-form-control" value="{{ old('birth_date') }}"
                                            required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">
                                        <i class="lni lni-map-marker"></i>
                                        Endereço *
                                    </label>
                                    <textarea id="address" name="address" class="form-control auth-form-control" rows="2" required
                                        placeholder="Sua morada completa">{{ old('address') }}</textarea>
                                </div>

                                <!-- Adicionar este campo específico para alunos -->
                                <div class="student-fields" id="studentFields">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label for="identity_document" class="form-label">
                                                <i class="lni lni-id-card"></i>
                                                Documento de Identificação *
                                            </label>
                                            <input type="text" id="identity_document" name="identity_document"
                                                class="form-control auth-form-control"
                                                value="{{ old('identity_document') }}" placeholder="BI/Passaporte">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="registration_number" class="form-label">
                                                <i class="lni lni-agenda"></i>
                                                Número de Matrícula (opcional)
                                            </label>
                                            <input type="text" id="registration_number" name="registration_number"
                                                class="form-control auth-form-control"
                                                value="{{ old('registration_number') }}"
                                                placeholder="Número da matrícula">
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos específicos por role (inicialmente ocultos) -->
                                <div id="roleSpecificFields">
                                    <!-- Campos para Aluno -->
                                    <div class="student-fields" id="studentFields">
                                        <div class="mb-3">
                                            <label for="identity_document" class="form-label">
                                                <i class="lni lni-id-card"></i>
                                                Documento de Identificação
                                            </label>
                                            <input type="text" id="identity_document" name="identity_document"
                                                class="form-control auth-form-control" placeholder="BI/Passaporte">
                                            <div class="form-text">Número do documento oficial</div>
                                        </div>
                                    </div>

                                    <!-- Campos para Professor -->
                                    <div class="teacher-fields" id="teacherFields">
                                        <div class="mb-3">
                                            <label for="teacher_phone" class="form-label">
                                                <i class="lni lni-phone"></i>
                                                Telefone
                                            </label>
                                            <input type="tel" id="teacher_phone" name="phone"
                                                class="form-control auth-form-control" placeholder="+244 900 000 000">
                                        </div>
                                    </div>

                                    <!-- Campos para Responsável -->
                                    <div class="guardian-fields" id="guardianFields">
                                        <div class="mb-3">
                                            <label for="guardian_phone" class="form-label">
                                                <i class="lni lni-phone"></i>
                                                Telefone
                                            </label>
                                            <input type="tel" id="guardian_phone" name="phone"
                                                class="form-control auth-form-control" placeholder="+244 900 000 000">
                                            <div class="form-text">Para contato em caso de emergência</div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="role" name="role" value="">

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">
                                        <i class="lni lni-arrow-left"></i>
                                        Voltar
                                    </button>
                                    <button type="button" class="btn btn-ip-primary" onclick="nextStep(3)"
                                        id="nextStep2Btn" disabled>
                                        Próximo
                                        <i class="lni lni-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Passo 3: Confirmação e Termos -->
                            <div class="form-step" id="step3Form">
                                <h4 class="mb-4">Confirmação</h4>

                                <div class="card ip-card mb-4">
                                    <div class="card-body">
                                        <h6 class="card-title">Resumo da Conta</h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Nome:</small>
                                                <p id="summaryName" class="mb-2">-</p>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Email:</small>
                                                <p id="summaryEmail" class="mb-2">-</p>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Perfil:</small>
                                                <p id="summaryRole" class="mb-2">-</p>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Data:</small>
                                                <p id="summaryDate" class="mb-2">{{ date('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4 terms-checkbox">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms"
                                            name="terms" required>
                                        <label class="form-check-label" for="terms">
                                            Concordo com os
                                            <a href="#" class="terms-link">Termos de Uso</a>
                                            e
                                            <a href="#" class="terms-link">Política de Privacidade</a>
                                        </label>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="lni lni-information"></i>
                                    <small>
                                        Após o registro, sua conta precisará ser ativada pela administração do
                                        instituto.
                                        Você receberá um email quando isso acontecer.
                                    </small>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                                        <i class="lni lni-arrow-left"></i>
                                        Voltar
                                    </button>
                                    <button type="submit" class="btn btn-ip-primary" id="submitBtn">
                                        <i class="lni lni-checkmark-circle"></i>
                                        Criar Conta
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

    <!-- JavaScript -->
    <script>
        // Variáveis globais
        let currentStep = 1;
        let selectedRole = '';

        // Esperar o DOM carregar
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar conteúdo e esconder loading
            setTimeout(() => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('authContent').style.display = 'block';

                // Inicializar data no resumo
                document.getElementById('summaryDate').textContent = new Date().toLocaleDateString('pt-PT');

                // Preencher dados do formulário se houver valores antigos
                const oldName = document.getElementById('name').value;
                const oldEmail = document.getElementById('email').value;

                if (oldName) document.getElementById('summaryName').textContent = oldName;
                if (oldEmail) document.getElementById('summaryEmail').textContent = oldEmail;

                // Restaurar role selecionado se houver
                const oldRole = "{{ old('role') }}";
                if (oldRole) {
                    selectRole(oldRole);
                    updateSummary();
                }
            }, 500);

            // Form submission handler
            const registerForm = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');

            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    // Validar termos
                    if (!document.getElementById('terms').checked) {
                        e.preventDefault();
                        alert('Por favor, aceite os termos de uso para continuar.');
                        return;
                    }

                    // Validar role selecionado
                    if (!selectedRole) {
                        e.preventDefault();
                        alert('Por favor, selecione um perfil.');
                        return;
                    }

                    const originalHTML = submitBtn.innerHTML;

                    submitBtn.innerHTML = `
                        <div class="auth-loader"></div>
                        <span>Criando conta...</span>
                    `;
                    submitBtn.disabled = true;

                    // Reset após 10 segundos
                    setTimeout(() => {
                        submitBtn.innerHTML = originalHTML;
                        submitBtn.disabled = false;
                    }, 10000);
                });
            }
        });

        // Funções de navegação entre passos
        function nextStep(step) {
            // Validar passo atual antes de avançar
            if (currentStep === 1 && !validateStep1()) {
                return;
            }

            if (currentStep === 2 && !validateStep2()) {
                return;
            }

            // Atualizar resumo antes de ir para o passo 3
            if (step === 3) {
                updateSummary();
            }

            // Esconder passo atual
            document.getElementById(`step${currentStep}Form`).classList.remove('active');
            document.getElementById(`step${currentStep}`).classList.remove('active');

            // Mostrar próximo passo
            document.getElementById(`step${step}Form`).classList.add('active');
            document.getElementById(`step${step}`).classList.add('active');

            // Marcar passo anterior como completado
            if (currentStep < step) {
                document.getElementById(`step${currentStep}`).classList.add('completed');
            } else {
                document.getElementById(`step${currentStep}`).classList.remove('completed');
            }

            currentStep = step;
        }

        function prevStep(step) {
            // Esconder passo atual
            document.getElementById(`step${currentStep}Form`).classList.remove('active');
            document.getElementById(`step${currentStep}`).classList.remove('active');

            // Mostrar passo anterior
            document.getElementById(`step${step}Form`).classList.add('active');
            document.getElementById(`step${step}`).classList.add('active');

            // Remover completado do passo atual
            document.getElementById(`step${currentStep}`).classList.remove('completed');

            currentStep = step;
        }

        // Validações
        function validateStep1() {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (!name.trim()) {
                alert('Por favor, insira seu nome completo.');
                return false;
            }

            if (!email.trim()) {
                alert('Por favor, insira seu email.');
                return false;
            }

            // Validação simples de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Por favor, insira um email válido.');
                return false;
            }

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

        function validateStep2() {
            if (!selectedRole) {
                alert('Por favor, selecione um perfil.');
                return false;
            }

            // Validações específicas por role
            if (selectedRole === 'student') {
                const identityDoc = document.getElementById('identity_document').value;
                if (!identityDoc.trim()) {
                    alert('Por favor, insira o documento de identificação.');
                    return false;
                }
            }

            return true;
        }

        // Seleção de role
        function selectRole(role) {
            selectedRole = role;

            // Atualizar input hidden
            document.getElementById('role').value = role;

            // Remover active de todos os cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('active');
            });

            // Adicionar active ao card selecionado
            document.getElementById(`role${role.charAt(0).toUpperCase() + role.slice(1)}`).classList.add('active');

            // Mostrar campos específicos
            document.querySelectorAll('.student-fields, .teacher-fields, .guardian-fields').forEach(field => {
                field.classList.remove('show');
            });

            document.getElementById(`${role}Fields`).classList.add('show');

            // Habilitar botão próximo
            document.getElementById('nextStep2Btn').disabled = false;

            // Atualizar botão com texto específico
            const nextBtn = document.getElementById('nextStep2Btn');
            nextBtn.innerHTML = `Continuar como ${getRoleName(role)} <i class="lni lni-arrow-right"></i>`;
        }

        function getRoleName(role) {
            switch (role) {
                case 'student':
                    return 'Aluno';
                case 'teacher':
                    return 'Professor';
                case 'guardian':
                    return 'Responsável';
                default:
                    return '';
            }
        }

        // Atualizar resumo
        function updateSummary() {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;

            document.getElementById('summaryName').textContent = name || '-';
            document.getElementById('summaryEmail').textContent = email || '-';
            document.getElementById('summaryRole').textContent = getRoleName(selectedRole) || '-';
        }

        // Verificar força da senha
        function checkPasswordStrength(password) {
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;
            let color = '#EF4444'; // Vermelho
            let text = 'Fraca';

            // Critérios de força
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
            strengthFill.style.width = width + '%';
            strengthFill.style.backgroundColor = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
        }

        // Verificar se as senhas coincidem
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchText = document.getElementById('passwordMatchText');

            if (confirmPassword) {
                if (password === confirmPassword) {
                    matchText.textContent = 'As senhas coincidem';
                    matchText.style.color = '#10B981';
                } else {
                    matchText.textContent = 'As senhas não coincidem';
                    matchText.style.color = '#EF4444';
                }
            } else {
                matchText.textContent = '';
            }
        }

        // Inicializar validação em tempo real
        document.getElementById('name').addEventListener('blur', function() {
            if (this.value) updateSummary();
        });

        document.getElementById('email').addEventListener('blur', function() {
            if (this.value) updateSummary();
        });
    </script>

    <!-- Vite JS -->
    @vite(['resources/js/app.js'])
</body>

</html>
