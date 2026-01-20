<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Turma;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'approved']);
        $this->middleware('role:admin|director')->except(['show']);
    }

    public function index(Request $request)
    {
        $query = Subject::withCount(['teachers', 'turmas', 'grades']);

        // Filtros
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->has('teacher_id')) {
            $query->whereHas('teachers', function($q) use ($request) {
                $q->where('teachers.id', $request->teacher_id);
            });
        }

        if ($request->has('turma_id')) {
            $query->whereHas('turmas', function($q) use ($request) {
                $q->where('turmas.id', $request->turma_id);
            });
        }

        $subjects = $query->latest()->paginate(20);

        // Estatísticas
        $totalSubjects = Subject::count();
        $totalTeachers = Teacher::count();
        $totalTurmas = Turma::count();
        $totalGrades = Grade::count();

        $teachers = Teacher::with('user')->get();
        $turmas = Turma::all();

        return view('subjects.index', compact(
            'subjects',
            'teachers',
            'turmas',
            'totalSubjects',
            'totalTeachers',
            'totalTurmas',
            'totalGrades'
        ));
    }

    public function create()
    {
        $teachers = Teacher::with('user')->get();
        $turmas = Turma::all();

        return view('subjects.create', compact('teachers', 'turmas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:subjects',
            'description' => 'nullable|string|max:500',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id',
            'turmas' => 'nullable|array',
            'turmas.*' => 'exists:turmas,id',
        ]);

        DB::beginTransaction();

        try {
            // Criar disciplina
            $subject = Subject::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Atribuir professores
            if ($request->has('teachers')) {
                $subject->teachers()->sync($request->teachers);
            }

            // Atribuir turmas através dos professores
            if ($request->has('turmas')) {
                // Para cada professor, atribuir as turmas selecionadas
                foreach ($subject->teachers as $teacher) {
                    $teacher->turmas()->syncWithoutDetaching($request->turmas);
                }
            }

            DB::commit();

            return redirect()->route('subjects.show', $subject)
                             ->with('success', 'Disciplina criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erro ao criar disciplina: ' . $e->getMessage()]);
        }
    }

    public function show(Subject $subject)
    {
        $subject->load([
            'teachers.user',
            'turmas',
            'grades' => function($query) {
                $query->with(['student.user', 'student.turma'])
                      ->latest()
                      ->take(10);
            }
        ]);

        // Estatísticas
        $totalTeachers = $subject->teachers()->count();
        $totalTurmas = $subject->turmas()->count();
        $totalGrades = $subject->grades()->count();
        $avgGrade = $subject->grades()->avg('value') ?? 0;

        // Alunos matriculados na disciplina
        $students = \App\Models\Student::whereHas('turma', function($q) use ($subject) {
            $q->whereHas('teachers', function($q2) use ($subject) {
                $q2->whereHas('subjects', function($q3) use ($subject) {
                    $q3->where('subjects.id', $subject->id);
                });
            });
        })->with('user')->count();

        // Distribuição de notas
        $gradeDistribution = [
            '0-4' => $subject->grades()->whereBetween('value', [0, 4])->count(),
            '5-9' => $subject->grades()->whereBetween('value', [5, 9])->count(),
            '10-14' => $subject->grades()->whereBetween('value', [10, 14])->count(),
            '15-20' => $subject->grades()->whereBetween('value', [15, 20])->count(),
        ];

        return view('subjects.show', compact(
            'subject',
            'totalTeachers',
            'totalTurmas',
            'totalGrades',
            'avgGrade',
            'students',
            'gradeDistribution'
        ));
    }

    public function edit(Subject $subject)
    {
        $teachers = Teacher::with('user')->get();
        $turmas = Turma::all();
        $subject->load(['teachers', 'turmas']);

        return view('subjects.edit', compact('subject', 'teachers', 'turmas'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:subjects,name,' . $subject->id,
            'description' => 'nullable|string|max:500',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id',
            'turmas' => 'nullable|array',
            'turmas.*' => 'exists:turmas,id',
        ]);

        DB::beginTransaction();

        try {
            // Atualizar disciplina
            $subject->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Atualizar professores
            if ($request->has('teachers')) {
                $subject->teachers()->sync($request->teachers);
            }

            // Atualizar turmas para todos os professores da disciplina
            if ($request->has('turmas')) {
                foreach ($subject->teachers as $teacher) {
                    $teacher->turmas()->sync($request->turmas);
                }
            }

            DB::commit();

            return redirect()->route('subjects.show', $subject)
                             ->with('success', 'Disciplina atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar disciplina: ' . $e->getMessage()]);
        }
    }

    public function destroy(Subject $subject)
    {
        DB::beginTransaction();

        try {
            // Remover todas as associações com professores
            $subject->teachers()->detach();

            // Remover todas as notas associadas
            $subject->grades()->delete();

            // Excluir a disciplina
            $subject->delete();

            DB::commit();

            return redirect()->route('subjects.index')
                             ->with('success', 'Disciplina excluída com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao excluir disciplina: ' . $e->getMessage()]);
        }
    }
}
