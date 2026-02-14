@section('title', 'Solicitar Inscrição')

<div>
    <div class="auth-card row g-0">
        <div class="col-md-5 auth-info d-none d-md-flex">
            <div class="w-100">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo-img mx-auto">
                <h3 class="mb-3">Junte-se a Nós</h3>
                <p class="mb-4">Faça parte da família IPP Alegria Pedro e tenha acesso a uma educação de excelência.
                </p>
                <div class="text-start">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle text-white bg-success rounded-circle p-2 me-3"></i>
                        <span>Excelência Acadêmica</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle text-white bg-success rounded-circle p-2 me-3"></i>
                        <span>Professores Qualificados</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-white bg-success rounded-circle p-2 me-3"></i>
                        <span>Tecnologia e Inovação</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7 auth-form">
            <div class="text-center mb-4 d-md-none">
                <h2 class="text-primary fw-bold">IPP Alegria Pedro</h2>
                <p class="text-muted">Solicitar Inscrição</p>
            </div>

            <h4 class="fw-bold mb-1">Criar Nova Conta</h4>
            <p class="text-muted mb-4">Preencha os dados abaixo para solicitar sua inscrição</p>

            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($this->getError('register'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $this->getError('register') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form wire:submit="{{ $step == 3 ? 'register' : 'nextStep' }}">
                <!-- Step Indicator -->
                <div class="step-indicator mb-5">
                    <div class="step-item">
                        <div
                            class="step @if ($step > 1) completed @elseif($step == 1) active @endif">
                            1</div>
                        <span class="step-label d-none d-md-block">Conta</span>
                    </div>
                    <div class="step-item">
                        <div
                            class="step @if ($step > 2) completed @elseif($step == 2) active @endif">
                            2</div>
                        <span class="step-label d-none d-md-block">Pessoal</span>
                    </div>
                    <div class="step-item">
                        <div
                            class="step @if ($step > 3) completed @elseif($step == 3) active @endif">
                            3</div>
                        <span class="step-label d-none d-md-block">Finalização</span>
                    </div>
                </div>

                <!-- Step 1: Informações Básicas -->
                @if ($step == 1)
                    <div class="fade-in" wire:key="step-1">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Tipo de Usuário <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @if ($this->hasError('role')) is-invalid @endif"
                                wire:model.live="role">
                                <option value="">Selecione o tipo de conta</option>
                                <option value="student">👨‍🎓 Aluno</option>
                                <option value="teacher">👨‍🏫 Professor</option>
                                <option value="parent">👪 Responsável/Encarregado</option>
                            </select>
                            @if ($this->hasError('role'))
                                <div class="invalid-feedback">{{ $this->getError('role') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @if ($this->hasError('name')) is-invalid @endif"
                                placeholder="Digite seu nome completo" wire:model="name">
                            @if ($this->hasError('name'))
                                <div class="invalid-feedback">{{ $this->getError('name') }}</div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email"
                                        class="form-control @if ($this->hasError('email')) is-invalid @endif"
                                        placeholder="seu@email.com" wire:model="email">
                                </div>
                                @if ($this->hasError('email'))
                                    <div class="invalid-feedback d-block">{{ $this->getError('email') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Telefone <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text"
                                        class="form-control @if ($this->hasError('phone')) is-invalid @endif"
                                        placeholder="(+244) 900 000 000" wire:model="phone">
                                </div>
                                @if ($this->hasError('phone'))
                                    <div class="invalid-feedback d-block">{{ $this->getError('phone') }}</div>
                                @endif
                            </div>
                        </div>

                        <!-- Apenas a parte dos campos de senha - Step 1 -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Senha <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password"
                                        class="form-control @if ($this->hasError('password')) is-invalid @endif"
                                        placeholder="Mínimo 8 caracteres" wire:model="password">
                                </div>
                                @if ($this->hasError('password'))
                                    <div class="invalid-feedback d-block">{{ $this->getError('password') }}</div>
                                @endif
                                @if (!$this->hasError('password') && strlen($password ?? '') > 0 && strlen($password) < 8)
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        A senha deve ter pelo menos 8 caracteres
                                    </small>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Confirmar Senha <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password"
                                        class="form-control @if ($this->hasError('password_confirmation')) is-invalid @endif"
                                        placeholder="Confirme sua senha" wire:model="password_confirmation">
                                </div>
                                @if ($this->hasError('password_confirmation'))
                                    <div class="invalid-feedback d-block">
                                        {{ $this->getError('password_confirmation') }}</div>
                                @endif
                                @if (
                                    !$this->hasError('password_confirmation') &&
                                        $password &&
                                        $password_confirmation &&
                                        $password !== $password_confirmation)
                                    <small class="text-danger">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        As senhas não coincidem
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Após o envio, sua conta será analisada pela secretaria. Você receberá um email quando for
                            aprovada.
                        </div>
                    </div>
                @endif

                <!-- Step 2: Informações Pessoais -->
                @if ($step == 2)
                    <div class="fade-in" wire:key="step-2">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Data de Nascimento <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @if ($this->hasError('birth_date')) is-invalid @endif"
                                    wire:model="birth_date" max="{{ date('Y-m-d') }}">
                                @if ($this->hasError('birth_date'))
                                    <div class="invalid-feedback">{{ $this->getError('birth_date') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Gênero</label>
                                <select class="form-select @if ($this->hasError('gender')) is-invalid @endif"
                                    wire:model="gender">
                                    <option value="">Selecionar</option>
                                    <option value="male">Masculino</option>
                                    <option value="female">Feminino</option>
                                    <option value="other">Outro</option>
                                </select>
                                @if ($this->hasError('gender'))
                                    <div class="invalid-feedback">{{ $this->getError('gender') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Endereço <span class="text-danger">*</span></label>
                            <textarea class="form-control @if ($this->hasError('address')) is-invalid @endif" rows="3"
                                placeholder="Digite seu endereço completo" wire:model="address"></textarea>
                            @if ($this->hasError('address'))
                                <div class="invalid-feedback">{{ $this->getError('address') }}</div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Nacionalidade</label>
                                <input type="text"
                                    class="form-control @if ($this->hasError('nationality')) is-invalid @endif"
                                    placeholder="ex: Angolana" wire:model="nationality">
                                @if ($this->hasError('nationality'))
                                    <div class="invalid-feedback">{{ $this->getError('nationality') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Documento de Identificação <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @if ($this->hasError('id_number')) is-invalid @endif"
                                    placeholder="BI/Passaporte" wire:model="id_number">
                                @if ($this->hasError('id_number'))
                                    <div class="invalid-feedback">{{ $this->getError('id_number') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Step 3: Informações Específicas -->
                @if ($step == 3)
                    <div class="fade-in" wire:key="step-3-{{ $role }}">
                        @if ($role === 'student')
                            <!-- Aluno -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Número de Estudante <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @if ($this->hasError('student_number')) is-invalid @endif"
                                    placeholder="Número de matrícula" wire:model="student_number">
                                @if ($this->hasError('student_number'))
                                    <div class="invalid-feedback">{{ $this->getError('student_number') }}</div>
                                @endif
                                <small class="text-muted">Número fornecido pela instituição</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Ano Lectivo</label>
                                    <select class="form-select @if ($this->hasError('academic_year')) is-invalid @endif"
                                        wire:model="academic_year">
                                        @for ($i = date('Y'); $i <= date('Y') + 5; $i++)
                                            <option value="{{ $i }}">
                                                {{ $i }}/{{ $i + 1 }}</option>
                                        @endfor
                                    </select>
                                    @if ($this->hasError('academic_year'))
                                        <div class="invalid-feedback">{{ $this->getError('academic_year') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Curso/Área</label>
                                    <input type="text"
                                        class="form-control @if ($this->hasError('course_area')) is-invalid @endif"
                                        placeholder="Ex: Ciências Físicas" wire:model="course_area">
                                    @if ($this->hasError('course_area'))
                                        <div class="invalid-feedback">{{ $this->getError('course_area') }}</div>
                                    @endif
                                </div>
                            </div>
                        @elseif($role === 'teacher')
                            <!-- Professor -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Qualificação Acadêmica <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @if ($this->hasError('qualification')) is-invalid @endif"
                                    placeholder="Ex: Licenciatura em Matemática" wire:model="qualification">
                                @if ($this->hasError('qualification'))
                                    <div class="invalid-feedback">{{ $this->getError('qualification') }}</div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Áreas de Especialização <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @if ($this->hasError('specializations')) is-invalid @endif"
                                    wire:model="specializations" multiple>
                                    <option value="mathematics">Matemática</option>
                                    <option value="physics">Física</option>
                                    <option value="chemistry">Química</option>
                                    <option value="biology">Biologia</option>
                                    <option value="portuguese">Língua Portuguesa</option>
                                    <option value="english">Inglês</option>
                                    <option value="history">História</option>
                                    <option value="geography">Geografia</option>
                                    <option value="philosophy">Filosofia</option>
                                    <option value="informatics">Informática</option>
                                </select>
                                @if ($this->hasError('specializations'))
                                    <div class="invalid-feedback">{{ $this->getError('specializations') }}</div>
                                @endif
                                <small class="text-muted">Segure Ctrl para selecionar múltiplas opções</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Anos de Experiência</label>
                                <input type="number"
                                    class="form-control @if ($this->hasError('experience_years')) is-invalid @endif"
                                    min="0" max="50" wire:model="experience_years">
                                @if ($this->hasError('experience_years'))
                                    <div class="invalid-feedback">{{ $this->getError('experience_years') }}</div>
                                @endif
                            </div>
                        @elseif($role === 'parent')
                            <!-- Responsável -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Email do Estudante <span
                                        class="text-danger">*</span></label>
                                <input type="email"
                                    class="form-control @if ($this->hasError('student_email')) is-invalid @endif"
                                    placeholder="email@estudante.com" wire:model="student_email">
                                @if ($this->hasError('student_email'))
                                    <div class="invalid-feedback">{{ $this->getError('student_email') }}</div>
                                @endif
                                <small class="text-muted">Email do aluno que você é responsável</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Parentesco <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @if ($this->hasError('relationship')) is-invalid @endif"
                                    wire:model="relationship">
                                    <option value="">Selecione o parentesco</option>
                                    <option value="father">Pai</option>
                                    <option value="mother">Mãe</option>
                                    <option value="guardian">Tutor/Responsável</option>
                                    <option value="other">Outro</option>
                                </select>
                                @if ($this->hasError('relationship'))
                                    <div class="invalid-feedback">{{ $this->getError('relationship') }}</div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Observações</label>
                                <textarea class="form-control @if ($this->hasError('parent_notes')) is-invalid @endif" rows="2"
                                    placeholder="Informações adicionais..." wire:model="parent_notes"></textarea>
                                @if ($this->hasError('parent_notes'))
                                    <div class="invalid-feedback">{{ $this->getError('parent_notes') }}</div>
                                @endif
                            </div>
                        @endif

                        <!-- Termos e Condições -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @if ($this->hasError('accept_terms')) is-invalid @endif"
                                    type="checkbox" id="terms" wire:model="accept_terms">
                                <label class="form-check-label" for="terms">
                                    Concordo com os
                                    <a href="#" class="text-primary">Termos de Uso</a> e
                                    <a href="#" class="text-primary">Política de Privacidade</a>
                                </label>
                            </div>
                            @if ($this->hasError('accept_terms'))
                                <div class="text-danger small">{{ $this->getError('accept_terms') }}</div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    @if ($step > 1)
                        <button type="button" class="btn btn-outline-primary" wire:click="previousStep">
                            <i class="fas fa-arrow-left me-2"></i> Voltar
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Voltar ao Login
                        </a>
                    @endif

                    @if ($step < 3)
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove>Próximo <i class="fas fa-arrow-right ms-2"></i></span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-2"></span> Processando...
                            </span>
                        </button>
                    @else
                        <button type="submit" class="btn btn-success">
                            <span wire:loading.remove>Finalizar Inscrição <i class="fas fa-check ms-2"></i></span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-2"></span> Enviando...
                            </span>
                        </button>
                    @endif
                </div>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted small">
                    Já tem uma conta?
                    <a href="{{ route('login') }}" class="text-primary fw-medium">Faça login aqui</a>
                </p>
            </div>
        </div>
    </div>
</div>
