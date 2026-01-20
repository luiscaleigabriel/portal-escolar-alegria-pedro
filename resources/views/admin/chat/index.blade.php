@extends('layouts.app')

@section('title', 'Chat - Monitoramento')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Monitoramento de Chats</h1>
            <p class="mb-0 text-muted">Visualize e gerencie conversas entre professores, alunos e responsáveis</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newChatModal">
                <i class="lni lni-plus me-1"></i>
                Nova Conversa
            </button>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="lni lni-comments display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $threads->total() }}</h2>
                    <p class="text-muted mb-0">Conversas Ativas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="lni lni-message display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $totalMessages }}</h2>
                    <p class="text-muted mb-0">Total de Mensagens</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                            <i class="lni lni-users display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $totalParticipants }}</h2>
                    <p class="text-muted mb-0">Participantes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                            <i class="lni lni-alarm-clock display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $activeToday }}</h2>
                    <p class="text-muted mb-0">Ativas Hoje</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Lista de Conversas -->
        <div class="col-xl-4 col-lg-5">
            <div class="card ip-card h-100">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-comments me-2"></i>
                        Conversas Ativas
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="chat-list">
                        @forelse($threads as $thread)
                        <div class="chat-list-item {{ $activeThread && $activeThread->id == $thread->id ? 'active' : '' }}"
                             data-thread-id="{{ $thread->id }}"
                             onclick="loadThread({{ $thread->id }})">
                            <div class="d-flex align-items-center p-3">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                        <i class="lni lni-comments-alt"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $thread->subject }}</h6>
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted">
                                            {{ $thread->participants->count() }} participantes
                                        </small>
                                        <span class="badge bg-primary ms-2">
                                            {{ $thread->messages->count() }}
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        Última mensagem: {{ $thread->updated_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                        <i class="lni lni-more-alt"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="loadThread({{ $thread->id }})">
                                                <i class="lni lni-eye me-2"></i>
                                                Ver Conversa
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="downloadChat({{ $thread->id }})">
                                                <i class="lni lni-download me-2"></i>
                                                Exportar
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#"
                                               onclick="archiveThread({{ $thread->id }})">
                                                <i class="lni lni-archive me-2"></i>
                                                Arquivar
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-light text-muted rounded-circle">
                                    <i class="lni lni-comments display-4"></i>
                                </div>
                            </div>
                            <h5 class="mb-2">Nenhuma conversa ativa</h5>
                            <p class="text-muted">Comece uma nova conversa clicando no botão acima.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Buscar conversas..." id="searchChats">
                        <button class="btn btn-outline-secondary" type="button" onclick="searchChats()">
                            <i class="lni lni-search-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Área de Conversa -->
        <div class="col-xl-8 col-lg-7">
            @if($activeThread)
            <div class="card ip-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0" id="chatSubject">{{ $activeThread->subject }}</h6>
                        <small class="text-muted" id="chatParticipants">
                            @php
                                $participants = $activeThread->participants->map(function($p) {
                                    return $p->user->name;
                                })->implode(', ');
                            @endphp
                            {{ $participants }}
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-info" onclick="refreshChat()">
                            <i class="lni lni-reload"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="downloadChat({{ $activeThread->id }})">
                            <i class="lni lni-download"></i>
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="lni lni-cog"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#" onclick="addParticipant()">
                                        <i class="lni lni-user-plus me-2"></i>
                                        Adicionar Participante
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="changeSubject()">
                                        <i class="lni lni-pencil-alt me-2"></i>
                                        Alterar Assunto
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                       onclick="archiveThread({{ $activeThread->id }})">
                                        <i class="lni lni-archive me-2"></i>
                                        Arquivar Conversa
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Mensagens -->
                <div class="card-body chat-messages" id="chatMessages">
                    @foreach($activeThread->messages->sortBy('created_at') as $message)
                    <div class="message {{ $message->sender_user_id == auth()->id() ? 'sent' : 'received' }}">
                        <div class="message-header">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs me-2">
                                    <div class="avatar-title bg-light rounded-circle text-primary">
                                        {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                    </div>
                                </div>
                                <strong>{{ $message->sender->name }}</strong>
                            </div>
                            <small class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="message-body">
                            {{ $message->content }}
                        </div>
                        @if($message->sender_user_id == auth()->id())
                        <div class="message-footer">
                            <small class="text-muted">
                                @if($message->created_at->diffInMinutes(now()) < 1)
                                Agora
                                @elseif($message->created_at->isToday())
                                {{ $message->created_at->format('H:i') }}
                                @else
                                {{ $message->created_at->format('d/m H:i') }}
                                @endif
                            </small>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Enviar Mensagem -->
                <div class="card-footer">
                    <form id="sendMessageForm" data-thread-id="{{ $activeThread->id }}">
                        @csrf
                        <div class="input-group">
                            <textarea class="form-control" id="messageInput"
                                      placeholder="Digite sua mensagem..." rows="2"></textarea>
                            <button class="btn btn-primary" type="submit" id="sendMessageBtn">
                                <i class="lni lni-arrow-right"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="lni lni-image"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="lni lni-attachment"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="lni lni-emoji-smile"></i>
                                </button>
                            </div>
                            <small class="text-muted">
                                Aperte Enter para enviar, Shift+Enter para nova linha
                            </small>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="card ip-card h-100">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="text-center">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle">
                                <i class="lni lni-comments display-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-2">Selecione uma conversa</h5>
                        <p class="text-muted">Escolha uma conversa da lista ao lado para visualizar as mensagens.</p>
                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#newChatModal">
                            <i class="lni lni-plus me-1"></i>
                            Iniciar Nova Conversa
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Nova Conversa -->
<div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.chat.store') }}" method="POST" id="newChatForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Iniciar Nova Conversa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="chatSubjectInput" class="form-label">Assunto da Conversa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="chatSubjectInput" name="subject" required
                               placeholder="Ex: Reunião de Pais, Dúvidas sobre Matemática, etc.">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Selecionar Participantes <span class="text-danger">*</span></label>
                        <div class="row">
                            <!-- Professores -->
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="lni lni-users me-2"></i>
                                            Professores
                                        </h6>
                                    </div>
                                    <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($teachers as $teacher)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input participant-checkbox"
                                                   type="checkbox"
                                                   value="{{ $teacher->user->id }}"
                                                   id="teacher_{{ $teacher->id }}"
                                                   data-type="teacher"
                                                   data-name="{{ $teacher->user->name }}">
                                            <label class="form-check-label" for="teacher_{{ $teacher->id }}">
                                                {{ $teacher->user->name }}
                                                <small class="text-muted d-block">{{ $teacher->user->email }}</small>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Alunos -->
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="lni lni-graduation me-2"></i>
                                            Alunos
                                        </h6>
                                    </div>
                                    <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($students as $student)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input participant-checkbox"
                                                   type="checkbox"
                                                   value="{{ $student->user->id }}"
                                                   id="student_{{ $student->id }}"
                                                   data-type="student"
                                                   data-name="{{ $student->user->name }}">
                                            <label class="form-check-label" for="student_{{ $student->id }}">
                                                {{ $student->user->name }}
                                                <small class="text-muted d-block">
                                                    Turma: {{ $student->turma->name ?? 'N/A' }}
                                                </small>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Responsáveis -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="lni lni-user me-2"></i>
                                            Responsáveis
                                        </h6>
                                    </div>
                                    <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                                        <div class="row">
                                            @foreach($guardians as $guardian)
                                            <div class="col-md-6">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input participant-checkbox"
                                                           type="checkbox"
                                                           value="{{ $guardian->user->id }}"
                                                           id="guardian_{{ $guardian->id }}"
                                                           data-type="guardian"
                                                           data-name="{{ $guardian->user->name }}">
                                                    <label class="form-check-label" for="guardian_{{ $guardian->id }}">
                                                        {{ $guardian->user->name }}
                                                        <small class="text-muted d-block">
                                                            {{ $guardian->students->count() }} aluno(s)
                                                        </small>
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Participantes Selecionados</label>
                        <div id="selectedParticipants" class="border rounded p-3 min-h-100">
                            <p class="text-muted mb-0 small">
                                Nenhum participante selecionado. Selecione pelo menos 2 participantes.
                            </p>
                        </div>
                        <input type="hidden" name="participants" id="participantsInput">
                    </div>

                    <div class="alert alert-info">
                        <i class="lni lni-information me-2"></i>
                        Uma conversa deve incluir pelo menos um professor, um aluno e seu responsável.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="createChatBtn" disabled>
                        <i class="lni lni-plus me-1"></i>
                        Criar Conversa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .chat-list {
        max-height: 600px;
        overflow-y: auto;
    }

    .chat-list-item {
        border-bottom: 1px solid var(--bs-border-color);
        cursor: pointer;
        transition: all 0.2s;
    }

    .chat-list-item:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.05);
    }

    .chat-list-item.active {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        border-left: 3px solid var(--bs-primary);
    }

    .chat-messages {
        height: 500px;
        overflow-y: auto;
        padding: 1rem;
        background-color: #f8f9fa;
    }

    .message {
        margin-bottom: 1rem;
        max-width: 80%;
    }

    .message.sent {
        margin-left: auto;
    }

    .message.received {
        margin-right: auto;
    }

    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .message-body {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: relative;
    }

    .message.sent .message-body {
        background: var(--bs-primary);
        color: white;
    }

    .message-footer {
        text-align: right;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .min-h-100 {
        min-height: 100px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Carregar thread
        window.loadThread = function(threadId) {
            window.location.href = `/admin/chat?thread=${threadId}`;
        };

        // Atualizar chat
        window.refreshChat = function() {
            if (window.activeThreadId) {
                loadThread(window.activeThreadId);
            }
        };

        // Exportar chat
        window.downloadChat = function(threadId) {
            fetch(`/admin/chat/${threadId}/export`)
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `chat-${threadId}-${new Date().toISOString().split('T')[0]}.txt`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                });
        };

        // Arquivar conversa
        window.archiveThread = function(threadId) {
            if (confirm('Tem certeza que deseja arquivar esta conversa?')) {
                fetch(`/admin/chat/${threadId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        };

        // Novo chat - gerenciar participantes
        const participantCheckboxes = document.querySelectorAll('.participant-checkbox');
        const selectedParticipantsDiv = document.getElementById('selectedParticipants');
        const participantsInput = document.getElementById('participantsInput');
        const createChatBtn = document.getElementById('createChatBtn');

        let selectedParticipants = [];

        participantCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const userId = this.value;
                const userName = this.dataset.name;
                const userType = this.dataset.type;

                if (this.checked) {
                    selectedParticipants.push({
                        id: userId,
                        name: userName,
                        type: userType
                    });
                } else {
                    selectedParticipants = selectedParticipants.filter(p => p.id !== userId);
                }

                updateSelectedParticipants();
                validateChatForm();
            });
        });

        function updateSelectedParticipants() {
            if (selectedParticipants.length === 0) {
                selectedParticipantsDiv.innerHTML = `
                    <p class="text-muted mb-0 small">
                        Nenhum participante selecionado. Selecione pelo menos 2 participantes.
                    </p>
                `;
                return;
            }

            let html = '<div class="d-flex flex-wrap gap-2">';
            selectedParticipants.forEach(participant => {
                const typeColors = {
                    teacher: 'warning',
                    student: 'primary',
                    guardian: 'info'
                };

                html += `
                    <span class="badge bg-${typeColors[participant.type]} d-flex align-items-center gap-1">
                        <i class="lni lni-${participant.type === 'teacher' ? 'users' :
                                          participant.type === 'student' ? 'graduation' : 'user'}"></i>
                        ${participant.name}
                        <button type="button" class="btn-close btn-close-white btn-sm ms-1"
                                onclick="removeParticipant('${participant.id}')"></button>
                    </span>
                `;
            });
            html += '</div>';

            selectedParticipantsDiv.innerHTML = html;

            // Atualizar input hidden
            participantsInput.value = JSON.stringify(selectedParticipants.map(p => p.id));
        }

        window.removeParticipant = function(userId) {
            selectedParticipants = selectedParticipants.filter(p => p.id !== userId);
            const checkbox = document.querySelector(`.participant-checkbox[value="${userId}"]`);
            if (checkbox) checkbox.checked = false;
            updateSelectedParticipants();
            validateChatForm();
        };

        function validateChatForm() {
            // Verificar se tem pelo menos 2 participantes
            const hasParticipants = selectedParticipants.length >= 2;

            // Verificar se tem pelo menos um professor
            const hasTeacher = selectedParticipants.some(p => p.type === 'teacher');

            createChatBtn.disabled = !hasParticipants || !hasTeacher;
        }

        // Enviar mensagem
        const sendMessageForm = document.getElementById('sendMessageForm');
        if (sendMessageForm) {
            const messageInput = document.getElementById('messageInput');
            const sendMessageBtn = document.getElementById('sendMessageBtn');

            sendMessageForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const message = messageInput.value.trim();
                if (!message) return;

                const threadId = this.dataset.threadId;
                const originalText = sendMessageBtn.innerHTML;

                // Mostrar loading
                sendMessageBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                `;
                sendMessageBtn.disabled = true;

                fetch(`/chat/threads/${threadId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        content: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Adicionar mensagem ao chat
                    addMessageToChat(data);
                    messageInput.value = '';

                    // Rolagem automática para a última mensagem
                    const chatMessages = document.getElementById('chatMessages');
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao enviar mensagem');
                })
                .finally(() => {
                    sendMessageBtn.innerHTML = originalText;
                    sendMessageBtn.disabled = false;
                });
            });

            // Atalho Enter para enviar, Shift+Enter para nova linha
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessageForm.dispatchEvent(new Event('submit'));
                }
            });
        }

        function addMessageToChat(messageData) {
            const chatMessages = document.getElementById('chatMessages');
            const messageTime = new Date(messageData.created_at).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });

            const messageHtml = `
                <div class="message sent">
                    <div class="message-header">
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-2">
                                <div class="avatar-title bg-light rounded-circle text-primary">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </div>
                            <strong>{{ auth()->user()->name }}</strong>
                        </div>
                        <small class="text-muted">${messageTime}</small>
                    </div>
                    <div class="message-body">
                        ${messageData.content}
                    </div>
                    <div class="message-footer">
                        <small class="text-muted">Agora</small>
                    </div>
                </div>
            `;

            chatMessages.insertAdjacentHTML('beforeend', messageHtml);
        }

        // Busca de conversas
        window.searchChats = function() {
            const searchTerm = document.getElementById('searchChats').value.toLowerCase();
            const chatItems = document.querySelectorAll('.chat-list-item');

            chatItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        };
    });
</script>
@endpush
@endsection
