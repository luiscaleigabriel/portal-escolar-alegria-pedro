<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Turma;
use App\Models\Grade;
use App\Models\Absence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // O middleware deve ser aplicado via construtor
        // $this->middleware(['auth', 'approved']);
    }

    public function index()
    {
        $user = auth()->user();

        // Se for admin/director, mostrar dashboard admin
        if ($user->hasRole(['admin', 'director'])) {
            return $this->adminDashboard();
        }

        // Verificar se o usuário está aprovado (redundante com middleware, mas seguro)
        if (!$user->isApproved()) {
            return redirect()->route('pending-approval');
        }

        // Verificar se o usuário tem o perfil correspondente
        if ($user->hasRole('student') && !$user->student) {
            return view('dashboard.profile-incomplete', [
                'message' => 'Seu perfil de aluno ainda não está completo. Aguarde a configuração pela administração.'
            ]);
        }

        if ($user->hasRole('teacher') && !$user->teacher) {
            return view('dashboard.profile-incomplete', [
                'message' => 'Seu perfil de professor ainda não está completo. Aguarde a configuração pela administração.'
            ]);
        }

        if ($user->hasRole('guardian') && !$user->guardian) {
            return view('dashboard.profile-incomplete', [
                'message' => 'Seu perfil de responsável ainda não está completo. Aguarde a configuração pela administração.'
            ]);
        }

        // Mostrar dashboard baseado no role
        if ($user->hasRole('teacher')) {
            return $this->teacherDashboard($user);
        } elseif ($user->hasRole('student')) {
            return $this->studentDashboard($user);
        } elseif ($user->hasRole('guardian')) {
            return $this->guardianDashboard($user);
        }

        // Fallback - se não tem role ou ocorreu erro
        return redirect()->route('profile.edit')
            ->with('warning', 'Seu perfil precisa ser configurado. Por favor, complete suas informações.');
    }

    private function adminDashboard()
    {
        $user = auth()->user();

        // Autorização extra para admin/director
        if (!$user->hasRole(['admin', 'director'])) {
            abort(403, 'Acesso não autorizado ao painel administrativo.');
        }

        $stats = [
            'pending_users' => User::where('status', 'pending')->count(),
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_turmas' => Turma::count(),
            'recent_registrations' => User::with('roles')
                ->latest()
                ->take(5)
                ->get(),
            'approved' => User::where('status', 'approved')->count(),
            'rejected' => User::where('status', 'rejected')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'by_role' => [
                'student' => User::whereHas('roles', fn($q) => $q->where('name', 'student'))->count(),
                'teacher' => User::whereHas('roles', fn($q) => $q->where('name', 'teacher'))->count(),
                'guardian' => User::whereHas('roles', fn($q) => $q->where('name', 'guardian'))->count(),
                'admin' => User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->count(),
                'director' => User::whereHas('roles', fn($q) => $q->where('name', 'director'))->count(),
            ]
        ];

        return view('admin.index', ['stats' => $stats]);
        // Se a view admin.index não existir, redirecionar para dashboard.admin
        if (view()->exists('admin.index')) {
            return view('admin.index', ['stats' => $stats]);
            echo "aaaa";
        } elseif (view()->exists('dashboard.admin')) {
            return view('admin.index', ['stats' => $stats]);
        } else {
            // Fallback básico
            return view('dashboard.admin-fallback', ['stats' => $stats]);
        }
    }

    private function teacherDashboard($user)
    {
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->route('profile.edit')
                ->with('error', 'Perfil de professor não encontrado. Contate a administração.');
        }

        // Carregar turmas com contagem de alunos
        $turmas = $teacher->turmas()
            ->withCount('students')
            ->get();

        // Obter IDs das turmas do professor
        $turmaIds = $turmas->pluck('id');

        // Estatísticas
        $data = [
            'teacher' => $teacher->load('user'),
            'turmas' => $turmas,
            'total_turmas' => $turmas->count(),
            'subjects' => $teacher->subjects()->count(),
            'total_students' => Student::whereIn('turma_id', $turmaIds)->count(),

            // Notas pendentes (lançamento)
            'grades_to_launch' => $this->getPendingGradesCount($teacher),

            // Faltas pendentes (registro)
            'absences_to_register' => $this->getPendingAbsencesCount($teacher),

            // Alunos recentemente avaliados
            'recent_grades' => Grade::whereHas('subject', function($query) use ($teacher) {
                $query->whereHas('teachers', function($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                });
            })
            ->with(['student.user', 'subject'])
            ->latest()
            ->take(5)
            ->get(),

            // Próximas atividades (simplificado)
            'upcoming_activities' => [],
        ];

        return view('dashboard.teacher', $data);
    }

    private function getPendingGradesCount($teacher)
    {
        // Lógica para contar notas pendentes de lançamento
        // Aqui você pode implementar sua lógica específica
        // Exemplo: contar alunos sem nota em avaliações recentes

        return 0; // Placeholder
    }

    private function getPendingAbsencesCount($teacher)
    {
        // Lógica para contar faltas pendentes de registro
        // Aqui você pode implementar sua lógica específica

        return 0; // Placeholder
    }

    private function studentDashboard($user)
    {
        $student = $user->student;

        if (!$student) {
            return redirect()->route('profile.edit')
                ->with('error', 'Perfil de aluno não encontrado. Contate a administração.');
        }

        // Calcular média das notas (evitando divisão por zero)
        $averageGrade = $student->grades()->avg('value') ?? 0;

        // Formatar para 2 casas decimais
        $formattedAverage = number_format($averageGrade, 2, ',', '.');

        // Obter notas com melhor performance (última nota por disciplina)
        $grades = $student->grades()
            ->with(['subject'])
            ->select('grades.*', DB::raw('MAX(grades.created_at) as latest_date'))
            ->groupBy('grades.subject_id')
            ->orderBy('latest_date', 'desc')
            ->take(5)
            ->get();

        // Obter faltas recentes
        $absences = $student->absences()
            ->with(['subject'])
            ->latest()
            ->take(5)
            ->get();

        // Calcular estatísticas de faltas
        $totalAbsences = $student->absences()->count();
        $justifiedAbsences = $student->absences()->where('justified', true)->count();
        $unjustifiedAbsences = $totalAbsences - $justifiedAbsences;

        // Próximas provas/atividades (simplificado)
        $upcomingExams = [];

        $data = [
            'student' => $student->load(['user', 'turma']),
            'turma' => $student->turma,
            'grades' => $grades,
            'absences' => $absences,
            'average_grade' => $formattedAverage,
            'total_absences' => $totalAbsences,
            'justified_absences' => $justifiedAbsences,
            'unjustified_absences' => $unjustifiedAbsences,
            'upcoming_exams' => $upcomingExams,
            'grade_status' => $this->getGradeStatus($averageGrade),
        ];

        return view('dashboard.student', $data);
    }

    private function getGradeStatus($average)
    {
        if ($average >= 14) {
            return ['status' => 'success', 'message' => 'Excelente desempenho!'];
        } elseif ($average >= 10) {
            return ['status' => 'warning', 'message' => 'Bom desempenho'];
        } else {
            return ['status' => 'danger', 'message' => 'Precisa melhorar'];
        }
    }

    private function guardianDashboard($user)
    {
        $guardian = $user->guardian;

        if (!$guardian) {
            return redirect()->route('profile.edit')
                ->with('error', 'Perfil de responsável não encontrado. Contate a administração.');
        }

        // Carregar alunos com suas informações principais
        $students = $guardian->students()
            ->with([
                'user',
                'turma',
                'grades' => function($query) {
                    $query->select('student_id', DB::raw('AVG(value) as average'))
                          ->groupBy('student_id');
                },
                'absences' => function($query) {
                    $query->select('student_id', DB::raw('COUNT(*) as total'))
                          ->groupBy('student_id');
                }
            ])
            ->get();

        // Calcular estatísticas por aluno
        $students = $students->map(function($student) {
            $student->average_grade = $student->grades->first()->average ?? 0;
            $student->total_absences = $student->absences->first()->total ?? 0;
            return $student;
        });

        // Calcular estatísticas gerais
        $totalStudents = $students->count();
        $averageGradeAll = $students->avg('average_grade') ?? 0;
        $totalAbsencesAll = $students->sum('total_absences');

        // Alertas (alunos com nota baixa ou muitas faltas)
        $alerts = $students->filter(function($student) {
            return ($student->average_grade < 10) || ($student->total_absences > 5);
        })->map(function($student) {
            $alerts = [];
            if ($student->average_grade < 10) {
                $alerts[] = 'Nota baixa';
            }
            if ($student->total_absences > 5) {
                $alerts[] = 'Muitas faltas';
            }
            return [
                'student' => $student,
                'alerts' => $alerts
            ];
        });

        $data = [
            'guardian' => $guardian->load('user'),
            'students' => $students,
            'total_students' => $totalStudents,
            'average_grade_all' => number_format($averageGradeAll, 2, ',', '.'),
            'total_absences_all' => $totalAbsencesAll,
            'alerts' => $alerts,
        ];

        return view('dashboard.guardian', $data);
    }
}
