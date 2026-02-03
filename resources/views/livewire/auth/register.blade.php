@section('title', 'Solicitar Inscrição')
<div>
    <div class="auth-card row g-0">
        <div class="col-md-5 auth-info d-none d-md-flex">
            <div class="w-100">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo-img mx-auto">
                <h3 class="mb-3">Junte-se a Nós</h3>
                <p class="mb-4"> Faça parte da família IPP Alegria Pedro e tenha acesso a uma educação de excelência.
                </p>
                <div class="text-start">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle text-white bg-success rounded-circle p-2 me-3"></i>
                        <span> Excelência Acadêmica</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle text-white bg-success rounded-circle p-2 me-3"></i>
                        <span> Professores Qualificados</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-white bg-success rounded-circle p-2 me-3"></i>
                        <span> Tecnologia e Inovação</span>
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
                <div class="alert alert-success fade-in">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="{{ $step == 3 ? 'register' : 'nextStep' }}">
                <!-- Step Indicator -->
                <div class="step-indicator mb-5">
                    @foreach ([1, 2, 3] as $stepNumber)
                        <div
                            class="step
                    @if ($stepNumber < $step) completed
                    @elseif($stepNumber == $step) active @endif">
                            {{ $stepNumber }}
                        </div>
                    @endforeach
                </div>

                <!-- Step 1: Informações Básicas -->
                @if ($step == 1)
                    <div class="fade-in">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Tipo de Usuário *</label>
                            <select class="form-select" wire:model="role" wire:change="updatedRole">
                                <option value="">Selecione o tipo de conta</option>
                                <option value="student">Aluno</option>
                                <option value="teacher">Professor</option>
                                <option value="parent">Responsável/Encarregado</option>
                            </select>
                            @error('role')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-medium">Nome Completo *</label>
                                <input type="text" class="form-control" placeholder="Digite seu nome completo"
                                    wire:model="name">
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Email *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" placeholder="seu@email.com"
                                        wire:model="email">
                                </div>
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Telefone *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" placeholder="(+244) 900 000 000"
                                        wire:model="phone">
                                </div>
                                @error('phone')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Senha *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" placeholder="Mínimo 8 caracteres"
                                        wire:model="password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Confirmar Senha *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" placeholder="Confirme sua senha"
                                        wire:model="password_confirmation">
                                </div>
                                @error('password_confirmation')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
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
                    <div class="fade-in">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Data de Nascimento *</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                    wire:model="birth_date" max="{{ date('Y-m-d') }}" required>
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Gênero</label>
                                <select class="form-select" wire:model="gender">
                                    <option value="">Selecionar</option>
                                    <option value="male">Masculino</option>
                                    <option value="female">Feminino</option>
                                    <option value="other">Outro</option>
                                </select>
                                @error('gender')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Endereço *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" rows="3"
                                placeholder="Digite seu endereço completo" wire:model="address" required></textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Nacionalidade</label>
                                <input type="text" class="form-control" placeholder="ex: Angolana"
                                    wire:model="nationality">
                                @error('nationality')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Documento de Identificação *</label>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                                    placeholder="BI/Passaporte" wire:model="id_number" required>
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Step 3: Informações Específicas -->
                @if ($step == 3)
                    <div class="fade-in">
                        @if ($role === 'student')
                            <!-- Aluno -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Número de Estudante *</label>
                                <input type="text" class="form-control" placeholder="Número de matrícula"
                                    wire:model="student_number">
                                @error('student_number')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Número fornecido pela instituição</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Ano Lectivo</label>
                                    <select class="form-select" wire:model="academic_year">
                                        @for ($i = date('Y'); $i <= date('Y') + 5; $i++)
                                            <option value="{{ $i }}">
                                                {{ $i }}/{{ $i + 1 }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Curso/Área</label>
                                    <input type="text" class="form-control" placeholder="Ex: Ciências Físicas"
                                        wire:model="course_area">
                                </div>
                            </div>
                        @elseif($role === 'teacher')
                            <!-- Professor -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Qualificação Acadêmica *</label>
                                <input type="text" class="form-control"
                                    placeholder="Ex: Licenciatura em Matemática" wire:model="qualification">
                                @error('qualification')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Áreas de Especialização *</label>
                                <select class="form-select" wire:model="specializations" multiple>
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
                                <small class="text-muted">Segure Ctrl para selecionar múltiplas opções</small>
                                @error('specializations')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Anos de Experiência</label>
                                <input type="number" class="form-control" min="0" max="50"
                                    wire:model="experience_years">
                            </div>
                        @elseif($role === 'parent')
                            <!-- Responsável -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Email do Estudante *</label>
                                <input type="email" class="form-control" placeholder="email@estudante.com"
                                    wire:model="student_email">
                                @error('student_email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                                <small class="text-muted">Email do aluno que você é responsável</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Parentesco *</label>
                                <select class="form-select" wire:model="relationship">
                                    <option value="">Selecione o parentesco</option>
                                    <option value="father">Pai</option>
                                    <option value="mother">Mãe</option>
                                    <option value="guardian">Tutor/Responsável</option>
                                    <option value="other">Outro</option>
                                </select>
                                @error('relationship')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Observações</label>
                                <textarea class="form-control" rows="2" placeholder="Informações adicionais..." wire:model="parent_notes"></textarea>
                            </div>
                        @endif

                        <!-- Termos e Condições -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms"
                                    wire:model="accept_terms">
                                <label class="form-check-label" for="terms">
                                    Concordo com os
                                    <a href="#" class="text-primary">Termos de Uso</a> e
                                    <a href="#" class="text-primary">Política de Privacidade</a>
                                </label>
                            </div>
                            @error('accept_terms')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    @if ($step > 1)
                        <button type="button" class="btn btn-outline-primary" wire:click="previousStep"
                            wire:loading.attr="disabled">
                            <i class="fas fa-arrow-left me-2"></i> Voltar
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Voltar ao Login
                        </a>
                    @endif

                    @if ($step < 3)
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Próximo <i class="fas fa-arrow-right ms-2"></i></span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-2"></span> Processando...
                            </span>
                        </button>
                    @else
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
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

<script>
    document.addEventListener('livewire:init', () => {
        // Limpar erros quando mudar de passo
        Livewire.on('step-changed', () => {
            // O Livewire já limpa os erros automaticamente ao mudar de passo
        });

        // Formatar telefone
        const phoneInput = document.querySelector('input[wire\\:model="phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    value = '(' + value.substring(0, 3) + ') ' + value.substring(3);
                }
                e.target.value = value;
            });
        }

        // Formatar data para o formato brasileiro ao exibir
        const birthDateInput = document.querySelector('input[wire\\:model="birth_date"]');
        if (birthDateInput) {
            birthDateInput.addEventListener('change', function(e) {
                if (e.target.value) {
                    const date = new Date(e.target.value);
                    const formattedDate = date.toLocaleDateString('pt-BR');
                    // Apenas para exibição, o valor real permanece no formato YYYY-MM-DD
                    console.log('Data formatada:', formattedDate);
                }
            });
        }
    });

    // Função para formatar CPF/BI (exemplo)
    function formatDocument(input) {
        let value = input.value.replace(/\D/g, '');

        if (value.length <= 11) {
            // Formato CPF: 000.000.000-00
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        } else {
            // Formato BI/Passaporte
            value = value.substring(0, 14);
        }

        input.value = value;
    }
</script>
