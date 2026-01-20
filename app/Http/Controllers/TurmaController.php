<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TurmaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'approved']);
        $this->middleware('role:admin|director')->except(['show']);
    }

    public function index(Request $request)
    {
        $query = Turma::withCount(['students', 'teachers', 'subjects']);

        // Filtros
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        if ($request->has('teacher_id')) {
            $query->whereHas('teachers', function($q) use ($request) {
                $q->where('teachers.id', $request->teacher_id);
            });
        }

        $turmas = $query->latest()->paginate(20);

        // Estatísticas
        $totalTurmas = Turma::count();
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();

        // Calcular média de alunos por turma
        $avgStudentsPerTurma = $totalTurmas > 0 ?
            round(Student::whereNotNull('turma_id')->count() / $totalTurmas, 1) : 0;

        $teachers = Teacher::with('user')->get();

        return view('turmas.index', compact(
            'turmas',
            'teachers',
            'totalTurmas',
            'totalStudents',
            'totalTeachers',
            'avgStudentsPerTurma'
        ));
    }

    public function create()
    {
        $teachers = Teacher::with('user')->get();
        $subjects = Subject::all();

        return view('turmas.create', compact('teachers', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:turmas',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        DB::beginTransaction();

        try {
            // Criar turma
            $turma = Turma::create([
                'name' => $request->name,
                'year' => $request->year,
            ]);

            // Atribuir professores
            if ($request->has('teachers')) {
                $turma->teachers()->sync($request->teachers);
            }

            // Atribuir disciplinas através dos professores
            if ($request->has('subjects')) {
                // Para cada professor, atribuir as disciplinas selecionadas
                foreach ($turma->teachers as $teacher) {
                    $teacher->subjects()->syncWithoutDetaching($request->subjects);
                }
            }

            DB::commit();

            return redirect()->route('turmas.show', $turma)
                             ->with('success', 'Turma criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erro ao criar turma: ' . $e->getMessage()]);
        }
    }

    public function show(Turma $turma)
    {
        $turma->load([
            'students.user',
            'teachers.user',
            'teachers.subjects'
        ]);

        // Estatísticas
        $totalStudents = $turma->students()->count();
        $totalTeachers = $turma->teachers()->count();
        $totalSubjects = Subject::whereHas('teachers', function($q) use ($turma) {
            $q->whereHas('turmas', function($q2) use ($turma) {
                $q2->where('turmas.id', $turma->id);
            });
        })->count();

        // Alunos sem responsável
        $studentsWithoutGuardian = $turma->students()
            ->whereDoesntHave('guardians')
            ->count();

        // Disciplinas da turma
        $subjects = Subject::whereHas('teachers', function($q) use ($turma) {
            $q->whereHas('turmas', function($q2) use ($turma) {
                $q2->where('turmas.id', $turma->id);
            });
        })->get();

        return view('turmas.show', compact(
            'turma',
            'totalStudents',
            'totalTeachers',
            'totalSubjects',
            'studentsWithoutGuardian',
            'subjects'
        ));
    }

    public function edit(Turma $turma)
    {
        $teachers = Teacher::with('user')->get();
        $subjects = Subject::all();
        $turma->load(['teachers', 'teachers.subjects']);

        return view('turmas.edit', compact('turma', 'teachers', 'subjects'));
    }

    public function update(Request $request, Turma $turma)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:turmas,name,' . $turma->id,
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        DB::beginTransaction();

        try {
            // Atualizar turma
            $turma->update([
                'name' => $request->name,
                'year' => $request->year,
            ]);

            // Atualizar professores
            if ($request->has('teachers')) {
                $turma->teachers()->sync($request->teachers);
            }

            // Atualizar disciplinas para todos os professores da turma
            if ($request->has('subjects')) {
                foreach ($turma->teachers as $teacher) {
                    $teacher->subjects()->sync($request->subjects);
                }
            }

            DB::commit();

            return redirect()->route('turmas.show', $turma)
                             ->with('success', 'Turma atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar turma: ' . $e->getMessage()]);
        }
    }

    public function destroy(Turma $turma)
    {
        DB::beginTransaction();

        try {
            // Remover todos os alunos da turma
            $turma->students()->update(['turma_id' => null]);

            // Remover todas as associações com professores
            $turma->teachers()->detach();

            // Excluir a turma
            $turma->delete();

            DB::commit();

            return redirect()->route('turmas.index')
                             ->with('success', 'Turma excluída com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao excluir turma: ' . $e->getMessage()]);
        }
    }
}
