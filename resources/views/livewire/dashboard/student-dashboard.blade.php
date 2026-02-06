@extends('layouts.app')

@section('page-title', 'Dashboard do Aluno')
@section('page-subtitle', 'Bem-vindo, ' . auth()->user()->name)

@section('content')
<div class="fade-in">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        @foreach($stats as $stat)
        <div class="col-xl-3 col-md-6">
            <div class="card-stat border-{{ $stat['color'] }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="card-stat-icon bg-{{ $stat['color'] }}-light">
                            <i class="{{ $stat['icon'] }}"></i>
                        </div>
                        <div class="card-stat-value text-dark mt-3">{{ $stat['value'] }}</div>
                        <div class="card-stat-label">{{ $stat['title'] }}</div>
                        @if($stat['change'])
                        <div class="card-stat-change">
                            <span class="{{ $stat['change_type'] === 'positive' ? 'change-positive' : 'change-negative' }}">
                                <i class="fas fa-arrow-{{ $stat['change_type'] === 'positive' ? 'up' : 'down' }} me-1"></i>
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

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Recent Grades -->
            <div class="card table-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Notas Recentes</h6>
                    <a href="{{ route('student.grades') }}" class="btn btn-sm btn-primary">Ver Todas</a>
                </div>
                <div class="card-body">
                    @if(count($recentGrades) > 0)
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>Disciplina</th>
                                    <th>Professor</th>
                                    <th>Tipo</th>
                                    <th>Nota</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentGrades as $grade)
                                <tr>
                                    <td class="fw-medium">{{ $grade['subject'] }}</td>
                                    <td>{{ $grade['teacher'] }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $grade['type'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge @if($grade['value'] >= 14) bg-success @elseif($grade['value'] >= 10) bg-warning @else bg-danger @endif">
                                            {{ $grade['value'] }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $grade['date'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhuma nota disponível</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- My Courses -->
            <div class="card border-none shadow-sm p-4 rounded-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold mb-0">Minhas Turmas e Disciplinas</h6>
                    <button class="btn btn-sm btn-outline-primary">Detalhes</button>
                </div>

                @if(count($courses) > 0)
                <div class="row g-3">
                    @foreach($courses as $course)
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0">{{ $course['name'] }}</h6>
                                <span class="badge bg-primary">{{ $course['level'] }}</span>
                            </div>
                            <small class="text-muted d-block mb-3">{{ $course['year'] }}</small>

                            <div class="mb-2">
                                <small class="text-muted">Disciplinas:</small>
                            </div>
                            @foreach($course['subjects'] as $subject)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $subject['name'] }}</span>
                                <small class="text-muted">{{ $subject['teacher'] }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-school fa-2x text-muted mb-3"></i>
                    <p class="text-muted">Nenhuma turma atribuída</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Pending Tasks -->
            <div class="card border-none shadow-sm p-4 rounded-4 bg-white mb-4">
                <h6 class="fw-bold mb-3">Tarefas Pendentes</h6>

                @if(count($pendingTasks) > 0)
                <div class="mb-4">
                    @foreach($pendingTasks as $task)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-medium mb-0">{{ $task['title'] }}</h6>
                            <span class="badge bg-{{ $task['status'] }}">
                                {{ $task['days_left'] }}
                            </span>
                        </div>
                        <small class="text-muted d-block mb-1">{{ $task['subject'] }} • {{ $task['teacher'] }}</small>
                        <small class="text-muted">Entrega: {{ $task['due_date'] }}</small>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('student.tasks') }}" class="btn btn-light w-100">Ver Todas as Tarefas</a>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                    <p class="text-muted">Nenhuma tarefa pendente</p>
                    <a href="{{ route('student.tasks') }}" class="btn btn-sm btn-outline-primary">Ver Tarefas</a>
                </div>
                @endif
            </div>

            <!-- Latest News -->
            <div class="card border-none shadow-sm p-4 rounded-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Últimas Notícias</h6>
                    <a href="{{ route('common.blog.index') }}" class="btn btn-sm btn-link">Ver Tudo</a>
                </div>

                @foreach($latestNews as $news)
                <div class="mb-3 pb-3 border-bottom">
                    <span class="badge bg-primary-light text-primary mb-2">{{ $news['category'] }}</span>
                    <p class="fw-medium mb-1">{{ $news['title'] }}</p>
                    <small class="text-muted d-block mb-2">{{ $news['excerpt'] }}</small>
                    <small class="text-muted"><i class="far fa-calendar me-1"></i> {{ $news['date'] }}</small>
                </div>
                @endforeach

                <a href="{{ route('common.blog.index') }}" class="btn btn-outline-primary w-100 mt-2">Aceder ao Blog</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Gráfico de notas (exemplo)
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('gradesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Set', 'Out', 'Nov', 'Dez', 'Jan', 'Fev'],
                    datasets: [{
                        label: 'Média por Mês',
                        data: [12, 14, 13, 15, 16, 16.5],
                        borderColor: '#003399',
                        backgroundColor: 'rgba(0, 51, 153, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 10,
                            max: 20
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
