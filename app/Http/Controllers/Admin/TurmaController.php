<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Turma;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TurmaController extends Controller
{
    public function index(Request $request)
    {
        $query = Turma::with(['teacher.user', 'students'])
            ->orderBy('name', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('grade_level', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $turmas = $query->paginate(20);
        $teachers = Teacher::with('user')->get();

        return view('admin.turmas.index', compact('turmas', 'teachers'));
    }

    public function create()
    {
        $teachers = Teacher::with('user')->where('status', 'active')->get();
        $subjects = Subject::where('status', 'active')->get();

        return view('admin.turmas.create', compact('teachers', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:turmas',
            'grade_level' => 'required|string|max:50',
            'school_year' => 'required|string|max:20',
            'teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'required|integer|min:1|max:50',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:500',

            // Disciplinas
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        DB::beginTransaction();

        try {
            // Cria a turma
            $turma = Turma::create([
                'name' => $request->name,
                'grade_level' => $request->grade_level,
                'school_year' => $request->school_year,
                'teacher_id' => $request->teacher_id,
                'capacity' => $request->capacity,
                'status' => $request->status,
                'description' => $request->description,
            ]);

            // Vincula disciplinas
            if ($request->filled('subject_ids')) {
                $turma->subjects()->sync($request->subject_ids);
            }

            DB::commit();

            return redirect()->route('admin.turmas.show', $turma)
                ->with('success', 'Turma criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao criar turma: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Turma $turma)
    {
        $turma->load(['teacher.user', 'students.user', 'subjects']);

        // Alunos disponíveis para adicionar
        $availableStudents = Student::with('user')
            ->where('status', 'active')
            ->where(function ($query) use ($turma) {
                $query->whereNull('turma_id')
                      ->orWhere('turma_id', '!=', $turma->id);
            })
            ->get();

        return view('admin.turmas.show', compact('turma', 'availableStudents'));
    }

    public function edit(Turma $turma)
    {
        $turma->load(['teacher', 'subjects']);
        $teachers = Teacher::with('user')->where('status', 'active')->get();
        $subjects = Subject::where('status', 'active')->get();

        return view('admin.turmas.edit', compact('turma', 'teachers', 'subjects'));
    }

    public function update(Request $request, Turma $turma)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('turmas')->ignore($turma->id)
            ],
            'grade_level' => 'required|string|max:50',
            'school_year' => 'required|string|max:20',
            'teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'required|integer|min:1|max:50',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:500',

            // Disciplinas
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        DB::beginTransaction();

        try {
            // Atualiza a turma
            $turma->update([
                'name' => $request->name,
                'grade_level' => $request->grade_level,
                'school_year' => $request->school_year,
                'teacher_id' => $request->teacher_id,
                'capacity' => $request->capacity,
                'status' => $request->status,
                'description' => $request->description,
            ]);

            // Atualiza disciplinas
            $turma->subjects()->sync($request->subject_ids ?? []);

            DB::commit();

            return redirect()->route('admin.turmas.show', $turma)
                ->with('success', 'Turma atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao atualizar turma: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Turma $turma)
    {
        DB::beginTransaction();

        try {
            // Remove vínculos
            $turma->subjects()->detach();

            // Remove alunos da turma (opcional - define turma_id como null)
            $turma->students()->update(['turma_id' => null]);

            // Remove a turma
            $turma->delete();

            DB::commit();

            return redirect()->route('admin.turmas.index')
                ->with('success', 'Turma removida com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao remover turma: ' . $e->getMessage());
        }
    }

    /**
     * Adiciona aluno à turma
     */
    public function addStudent(Request $request, Turma $turma)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = Student::find($request->student_id);

        // Verifica se há vagas
        if ($turma->students->count() >= $turma->capacity) {
            return redirect()->back()
                ->with('error', 'Turma está com capacidade máxima!');
        }

        $student->update(['turma_id' => $turma->id]);

        return redirect()->back()
            ->with('success', 'Aluno adicionado à turma com sucesso!');
    }

    /**
     * Remove aluno da turma
     */
    public function removeStudent(Turma $turma, Student $student)
    {
        if ($student->turma_id == $turma->id) {
            $student->update(['turma_id' => null]);
            return redirect()->back()
                ->with('success', 'Aluno removido da turma com sucesso!');
        }

        return redirect()->back()
            ->with('error', 'Aluno não pertence a esta turma.');
    }
}
