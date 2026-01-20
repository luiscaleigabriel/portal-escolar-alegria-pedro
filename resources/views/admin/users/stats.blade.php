@extends('layouts.app')

@section('title', 'Estatísticas de Usuários')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Estatísticas de Usuários</h1>
            <p class="mb-0 text-muted">Relatórios e análises do sistema</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="lni lni-arrow-left me-1"></i>
                Voltar
            </a>
            <button onclick="window.print()" class="btn btn-outline-primary">
                <i class="lni lni-printer me-1"></i>
                Imprimir
            </button>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="lni lni-users display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $stats['total'] }}</h2>
                    <p class="text-muted mb-0">Total de Usuários</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="lni lni-checkmark display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $stats['approved'] }}</h2>
                    <p class="text-muted mb-0">Aprovados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                            <i class="lni lni-timer display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $stats['pending'] }}</h2>
                    <p class="text-muted mb-0">Pendentes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card ip-card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                            <i class="lni lni-cross-circle display-5"></i>
                        </div>
                    </div>
                    <h2 class="mb-1">{{ $stats['rejected'] + $stats['suspended'] }}</h2>
                    <p class="text-muted mb-0">Bloqueados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Duas colunas principais -->
    <div class="row g-4">
        <!-- Coluna da Esquerda: Gráficos -->
        <div class="col-xl-8">
            <!-- Distribuição por Perfil -->
            <div class="card ip-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-pie-chart me-2"></i>
                        Distribuição por Perfil
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($stats['by_role'] as $role => $count)
                        <div class="col-md-4 col-6 mb-3">
                            <div class="text-center">
                                @php
                                    $roleIcons = [
                                        'student' => 'lni-graduation',
                                        'teacher' => 'lni-users',
                                        'guardian' => 'lni-user',
                                        'admin' => 'lni-cog',
                                        'director' => 'lni-star'
                                    ];
                                    $roleColors = [
                                        'student' => 'primary',
                                        'teacher' => 'warning',
                                        'guardian' => 'info',
                                        'admin' => 'danger',
                                        'director' => 'success'
                                    ];
                                    $percentage = $stats['total'] > 0 ? ($count / $stats['total'] * 100) : 0;
                                @endphp
                                <div class="position-relative d-inline-block mb-2">
                                    <div class="avatar-xl">
                                        <div class="avatar-title bg-{{ $roleColors[$role] }}-subtle text-{{ $roleColors[$role] }} rounded-circle">
                                            <i class="lni {{ $roleIcons[$role] }} display-4"></i>
                                        </div>
                                    </div>
                                    <div class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-{{ $roleColors[$role] }}">
                                        {{ number_format($percentage, 1) }}%
                                    </div>
                                </div>
                                <h4 class="mb-1">{{ $count }}</h4>
                                <p class="text-muted mb-0">{{ ucfirst($role) }}s</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Status Detalhado -->
            <div class="card ip-card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-stats-up me-2"></i>
                        Status Detalhado
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="mb-3">Status de Aprovação</h6>
                                <div class="progress-stacked mb-2">
                                    @php
                                        $statusData = [
                                            ['label' => 'Aprovados', 'count' => $stats['approved'], 'color' => 'success', 'percentage' => $stats['total'] > 0 ? ($stats['approved'] / $stats['total'] * 100) : 0],
                                            ['label' => 'Pendentes', 'count' => $stats['pending'], 'color' => 'warning', 'percentage' => $stats['total'] > 0 ? ($stats['pending'] / $stats['total'] * 100) : 0],
                                            ['label' => 'Rejeitados', 'count' => $stats['rejected'], 'color' => 'danger', 'percentage' => $stats['total'] > 0 ? ($stats['rejected'] / $stats['total'] * 100) : 0],
                                            ['label' => 'Suspensos', 'count' => $stats['suspended'], 'color' => 'secondary', 'percentage' => $stats['total'] > 0 ? ($stats['suspended'] / $stats['total'] * 100) : 0],
                                        ];
                                    @endphp

                                    @foreach($statusData as $status)
                                    <div class="progress" role="progressbar" style="width: {{ $status['percentage'] }}%">
                                        <div class="progress-bar bg-{{ $status['color'] }}" style="width: 100%"></div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="row small text-muted">
                                    @foreach($statusData as $status)
                                    <div class="col-6 mb-1">
                                        <i class="lni lni-circle-fill text-{{ $status['color'] }} me-1"></i>
                                        {{ $status['label'] }}: {{ $status['count'] }} ({{ number_format($status['percentage'], 1) }}%)
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-3">Crescimento Mensal</h6>
                            <div class="border rounded p-3">
                                <p class="text-muted small mb-0">
                                    <i class="lni lni-information me-1"></i>
                                    Em desenvolvimento. Aqui serão exibidas estatísticas de crescimento.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna da Direita: Informações -->
        <div class="col-xl-4">
            <!-- Resumo do Sistema -->
            <div class="card ip-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-information me-2"></i>
                        Resumo do Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Taxa de Aprovação</span>
                            <span class="badge bg-success">
                                @php
                                    $approvalRate = $stats['total'] > 0 ? ($stats['approved'] / $stats['total'] * 100) : 0;
                                @endphp
                                {{ number_format($approvalRate, 1) }}%
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Taxa de Pendência</span>
                            <span class="badge bg-warning">
                                @php
                                    $pendingRate = $stats['total'] > 0 ? ($stats['pending'] / $stats['total'] * 100) : 0;
                                @endphp
                                {{ number_format($pendingRate, 1) }}%
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Taxa de Rejeição</span>
                            <span class="badge bg-danger">
                                @php
                                    $rejectionRate = $stats['total'] > 0 ? ($stats['rejected'] / $stats['total'] * 100) : 0;
                                @endphp
                                {{ number_format($rejectionRate, 1) }}%
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Usuários Ativos</span>
                            <span class="badge bg-primary">
                                {{ $stats['approved'] }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Perfil Mais Comum</span>
                            <span class="badge bg-info">
                                @php
                                    $mostCommon = array_search(max($stats['by_role']), $stats['by_role']);
                                @endphp
                                {{ ucfirst($mostCommon) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exportação -->
            <div class="card ip-card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="lni lni-download me-2"></i>
                        Exportar Dados
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="exportData('pdf')">
                            <i class="lni lni-files me-2"></i>
                            Exportar para PDF
                        </button>
                        <button class="btn btn-outline-success" onclick="exportData('excel')">
                            <i class="lni lni-microsoft-excel me-2"></i>
                            Exportar para Excel
                        </button>
                        <button class="btn btn-outline-info" onclick="exportData('csv')">
                            <i class="lni lni-data-analytics me-2"></i>
                            Exportar para CSV
                        </button>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <small class="text-muted">
                        <i class="lni lni-calendar me-1"></i>
                        Gerado em: {{ now()->format('d/m/Y H:i:s') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .btn, .card-footer, .export-buttons {
            display: none !important;
        }

        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }

        .container-fluid {
            padding: 0 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function exportData(format) {
        // Aqui você implementaria a lógica de exportação
        alert(`Exportando dados para ${format.toUpperCase()}...\n\nEsta funcionalidade será implementada em breve.`);
    }
</script>
@endpush
@endsection
