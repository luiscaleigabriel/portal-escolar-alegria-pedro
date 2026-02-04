@section('page-title', 'Dashboard do Administrador')
@section('page-subtitle', 'Monitoramento do Sistema')

<div>
    <div class="fade-in">
        <!-- Estatísticas -->
        <div class="row g-4 mb-5">
            @foreach ($stats as $stat)
                <div class="col-xl-3 col-md-6">
                    <div class="card-stat border-{{ $stat['color'] }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="card-stat-icon bg-{{ $stat['color'] }}-light">
                                    <i class="{{ $stat['icon'] }}"></i>
                                </div>
                                <div class="card-stat-value text-dark mt-3">{{ $stat['value'] }}</div>
                                <div class="card-stat-label">{{ $stat['title'] }}</div>
                                @if ($stat['change'])
                                    <div class="card-stat-change">
                                        <span
                                            class="{{ $stat['change_type'] === 'positive' ? 'change-positive' : ($stat['change_type'] === 'negative' ? 'change-negative' : 'change-info') }}">
                                            @if ($stat['change_type'] === 'positive')
                                                <i class="fas fa-arrow-up me-1"></i>
                                            @elseif($stat['change_type'] === 'negative')
                                                <i class="fas fa-arrow-down me-1"></i>
                                            @else
                                                <i class="fas fa-info-circle me-1"></i>
                                            @endif
                                            {{ $stat['change'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Gráfico de Atividade -->
        <div class="row g-4 mb-5">
            <div class="col-lg-8">
                <div class="card border-none shadow-sm h-100">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Atividade dos Últimos 7 Dias</h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-calendar me-2"></i> 7 Dias
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Hoje</a></li>
                                <li><a class="dropdown-item" href="#">7 Dias</a></li>
                                <li><a class="dropdown-item" href="#">30 Dias</a></li>
                                <li><a class="dropdown-item" href="#">Este Ano</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="activityChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Status do Sistema -->
            <div class="col-lg-4">
                <div class="card border-none shadow-sm h-100">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="fw-bold mb-0">Status do Sistema</h6>
                    </div>
                    <div class="card-body">
                        @foreach ($systemHealth as $service)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <h6 class="fw-medium mb-1">{{ $service['name'] }}</h6>
                                    <small class="text-muted">Uptime: {{ $service['uptime'] }}</small>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $service['color'] }}">
                                        {{ $service['status'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Última Verificação:</span>
                                <strong>{{ now()->format('H:i:s') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Próxima Verificação:</span>
                                <strong>{{ now()->addMinutes(5)->format('H:i:s') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atividade Recente dos Usuários -->
        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card border-none shadow-sm h-100">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Atividade Recente dos Usuários</h6>
                        <span class="badge bg-primary">{{ count($userActivity) }} usuários</span>
                    </div>
                    <div class="card-body">
                        @if (count($userActivity) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Usuário</th>
                                            <th>Tipo</th>
                                            <th>Último Login</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($userActivity as $user)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user['name']) }}&background=0055ff&color=fff"
                                                            class="rounded-circle me-3" width="32" height="32">
                                                        <div>
                                                            <div class="fw-medium">{{ $user['name'] }}</div>
                                                            <small class="text-muted">ID: {{ $user['id'] }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge @switch($user['role'])
                                            @case('Administrador') bg-danger @break
                                            @case('Secretaria') bg-warning @break
                                            @case('Professor') bg-info @break
                                            @case('Aluno') bg-primary @break
                                            @default bg-secondary
                                        @endswitch">
                                                        {{ $user['role'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($user['last_login'] === 'Nunca logou')
                                                        <span class="text-muted">{{ $user['last_login'] }}</span>
                                                    @else
                                                        <span class="text-success">
                                                            <i class="fas fa-circle text-success me-1"
                                                                style="font-size: 8px;"></i>
                                                            {{ $user['last_login'] }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $user['status'] === 'active' ? 'success' : 'danger' }}">
                                                        {{ $user['status'] === 'active' ? 'Ativo' : 'Inativo' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users-slash fa-2x text-muted mb-3"></i>
                                <p class="text-muted">Nenhuma atividade registrada</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-top">
                        <a href="#" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-users me-2"></i> Ver Todos os Usuários
                        </a>
                    </div>
                </div>
            </div>

            <!-- Logs do Sistema -->
            <div class="col-lg-6">
                <div class="card border-none shadow-sm h-100">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Logs do Sistema</h6>
                        <button class="btn btn-sm btn-light">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @if (count($recentLogs) > 0)
                            <div class="timeline timeline-simple">
                                @foreach ($recentLogs as $log)
                                    <div class="timeline-item mb-3">
                                        <div class="timeline-item-marker">
                                            <div class="timeline-item-marker-text">
                                                {{ $log['time'] }}
                                            </div>
                                            <div
                                                class="timeline-item-marker-indicator bg-{{ $log['type'] === 'error' ? 'danger' : ($log['type'] === 'login' ? 'success' : 'info') }}">
                                                <i class="fas fa-{{ $this->getLogIcon($log['type']) }}"></i>
                                            </div>
                                        </div>
                                        <div class="timeline-item-content">
                                            <div class="card border-light">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="fw-medium mb-0">{{ $log['message'] }}</h6>
                                                        <span
                                                            class="badge bg-{{ $log['type'] === 'error' ? 'danger' : ($log['type'] === 'login' ? 'success' : 'info') }}">
                                                            {{ ucfirst($log['type']) }}
                                                        </span>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-network-wired me-1"></i>
                                                        IP: {{ $log['ip'] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                <p class="text-muted">Nenhum log disponível</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-top">
                        <a href="{{ route('admin.logs') }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-file-alt me-2"></i> Ver Todos os Logs
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-none shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="fw-bold mb-0">Ações Rápidas</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <a href="#" class="card border-none shadow-sm text-decoration-none text-dark">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-user-cog fa-2x text-primary"></i>
                                        </div>
                                        <h6 class="fw-bold mb-2">Gerenciar Usuários</h6>
                                        <p class="text-muted small mb-0">Adicionar, editar ou remover usuários</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('admin.settings') }}"
                                    class="card border-none shadow-sm text-decoration-none text-dark">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-cogs fa-2x text-success"></i>
                                        </div>
                                        <h6 class="fw-bold mb-2">Configurações</h6>
                                        <p class="text-muted small mb-0">Configurar sistema e preferências</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('admin.backup') }}"
                                    class="card border-none shadow-sm text-decoration-none text-dark">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-database fa-2x text-warning"></i>
                                        </div>
                                        <h6 class="fw-bold mb-2">Backup</h6>
                                        <p class="text-muted small mb-0">Criar ou restaurar backup</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('admin.logs') }}"
                                    class="card border-none shadow-sm text-decoration-none text-dark">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-history fa-2x text-info"></i>
                                        </div>
                                        <h6 class="fw-bold mb-2">Logs</h6>
                                        <p class="text-muted small mb-0">Visualizar logs do sistema</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                // Gráfico de Atividade
                const ctx = document.getElementById('activityChart');
                if (ctx) {
                    const activityData = @json($activityChart);

                    new Chart(ctx, {
                        type: 'line',
                        data: activityData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        callback: function(value) {
                                            if (Number.isInteger(value)) {
                                                return value;
                                            }
                                        }
                                    },
                                    grid: {
                                        drawBorder: false
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true,
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    borderColor: '#003399',
                                    borderWidth: 1,
                                    callbacks: {
                                        label: function(context) {
                                            return `${context.dataset.label}: ${context.parsed.y}`;
                                        }
                                    }
                                }
                            },
                            elements: {
                                point: {
                                    radius: 4,
                                    hoverRadius: 6
                                },
                                line: {
                                    tension: 0.4
                                }
                            }
                        }
                    });
                }

                // Atualizar dados a cada 60 segundos
                setInterval(() => {
                    Livewire.dispatch('refresh-dashboard');
                }, 60000);
            });

            // Função para formatar logs
            function formatLogType(type) {
                const types = {
                    'login': {
                        icon: 'fa-sign-in-alt',
                        color: 'success'
                    },
                    'error': {
                        icon: 'fa-exclamation-circle',
                        color: 'danger'
                    },
                    'update': {
                        icon: 'fa-sync-alt',
                        color: 'info'
                    },
                    'backup': {
                        icon: 'fa-database',
                        color: 'warning'
                    },
                    'warning': {
                        icon: 'fa-exclamation-triangle',
                        color: 'warning'
                    },
                    'info': {
                        icon: 'fa-info-circle',
                        color: 'info'
                    }
                };

                return types[type] || {
                    icon: 'fa-circle',
                    color: 'secondary'
                };
            }

            // Auto-refresh para status do sistema
            function refreshSystemStatus() {
                // Aqui você faria uma requisição AJAX para obter o status atual
                console.log('Atualizando status do sistema...');
            }

            // Iniciar auto-refresh a cada 30 segundos
            setInterval(refreshSystemStatus, 30000);

            // Navegação por teclado
            document.addEventListener('keydown', (e) => {
                // Ctrl + Shift + L = Logs
                if (e.ctrlKey && e.shiftKey && e.key === 'L') {
                    window.location.href = "{{ route('admin.logs') }}";
                }

                // Ctrl + Shift + U = Usuários
                if (e.ctrlKey && e.shiftKey && e.key === 'U') {
                    window.location.href = "{{ route('admin.users') }}";
                }

                // Ctrl + Shift + S = Configurações
                if (e.ctrlKey && e.shiftKey && e.key === 'S') {
                    window.location.href = "{{ route('admin.settings') }}";
                }

                // Ctrl + Shift + B = Backup
                if (e.ctrlKey && e.shiftKey && e.key === 'B') {
                    window.location.href = "{{ route('admin.backup') }}";
                }
            });

            // Notificação de atualização
            document.addEventListener('livewire:navigated', () => {
                const notification = document.getElementById('update-notification');
                if (notification) {
                    setTimeout(() => {
                        notification.classList.remove('show');
                    }, 5000);
                }
            });
        </script>
    @endsection

    <!-- Componente Livewire para notificações -->
    <div>
        @if (session('success'))
            <div id="update-notification" class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
                <div class="toast show" role="alert">
                    <div class="toast-header bg-success text-white">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong class="me-auto">Sucesso</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div id="error-notification" class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
                <div class="toast show" role="alert">
                    <div class="toast-header bg-danger text-white">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong class="me-auto">Erro</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div id="warning-notification" class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
                <div class="toast show" role="alert">
                    <div class="toast-header bg-warning text-white">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong class="me-auto">Aviso</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('warning') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal para detalhes do sistema -->
    <div class="modal fade" id="systemDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Sistema</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Informações do Servidor</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>PHP Version:</strong></td>
                                    <td>{{ phpversion() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Laravel Version:</strong></td>
                                    <td>{{ app()->version() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Server Software:</strong></td>
                                    <td>{{ $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Timezone:</strong></td>
                                    <td>{{ config('app.timezone') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Environment:</strong></td>
                                    <td>{{ config('app.env') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Estatísticas</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Memory Usage:</strong></td>
                                    <td>{{ round(memory_get_usage() / 1024 / 1024, 2) }} MB</td>
                                </tr>
                                <tr>
                                    <td><strong>Peak Memory:</strong></td>
                                    <td>{{ round(memory_get_peak_usage() / 1024 / 1024, 2) }} MB</td>
                                </tr>
                                <tr>
                                    <td><strong>Execution Time:</strong></td>
                                    <td>{{ round(microtime(true) - LARAVEL_START, 2) }}s</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="refreshSystemInfo()">
                        <i class="fas fa-sync-alt me-2"></i> Atualizar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
