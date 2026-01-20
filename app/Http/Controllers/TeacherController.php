<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Turma;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'approved']);
        $this->middleware('role:admin|director')->except(['show']);
    }

    public function index(Request $request)
    {
        $query = Teacher::with(['user', 'subjects', 'turmas']);

        // Filtros
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('subject_id')) {
            $query->whereHas('subjects', function($q) use ($request) {
                $q->where('subjects.id', $request->subject_id);
            });
        }

        if ($request->has('turma_id')) {
            $query->whereHas('turmas', function($q) use ($request) {
                $q->where('turmas.id', $request->turma_id);
            });
        }

        $teachers = $query->latest()->paginate(20);

        // Estatísticas
        $totalTeachers = Teacher::count();
        $activeTeachers = Teacher::whereHas('user', function($q) {
            $q->where('status', 'approved');
        })->count();
        $subjects = Subject::all();
        $totalSubjects = $subjects->count();
        $turmas = Turma::all();
        $totalTurmas = $turmas->count();

        return view('teachers.index', compact(
            'teachers',
            'subjects',
            'turmas',
            'totalTeachers',
            'activeTeachers',
            'totalSubjects',
            'totalTurmas'
        ));
    }

    public function create()
    {
        $subjects = Subject::all();
        $turmas = Turma::all();

        return view('teachers.create', compact('subjects', 'turmas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'address' => 'required|string|max:500',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'turmas' => 'nullable|array',
            'turmas.*' => 'exists:turmas,id',
        ]);

        DB::beginTransaction();

        try {
            // Criar usuário
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'address' => $request->address,
                'gender' => $request->gender,
                'status' => 'approved',
            ]);

            // Atribuir role de teacher
            $user->assignRole('teacher');

            // Criar perfil de professor
            $teacher = Teacher::create([
                'user_id' => $user->id,
            ]);

            // Atribuir disciplinas e turmas
            if ($request->has('subjects')) {
                $teacher->subjects()->sync($request->subjects);
            }

            if ($request->has('turmas')) {
                $teacher->turmas()->sync($request->turmas);
            }

            DB::commit();

            return redirect()->route('teachers.show', $teacher)
                             ->with('success', 'Professor criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erro ao criar professor: ' . $e->getMessage()]);
        }
    }

    public function show(Teacher $teacher)
    {
        $this->authorize('view', $teacher);

        $teacher->load(['user', 'subjects', 'turmas.students.user']);

        // Estatísticas
        $totalTurmas = $teacher->turmas()->count();
        $totalSubjects = $teacher->subjects()->count();
        $totalStudents = Student::whereIn('turma_id', $teacher->turmas()->pluck('id'))->count();

        return view('teachers.show', compact(
            'teacher',
            'totalTurmas',
            'totalSubjects',
            'totalStudents'
        ));
    }

    public function edit(Teacher $teacher)
    {
        $subjects = Subject::all();
        $turmas = Turma::all();
        $teacher->load(['subjects', 'turmas']);

        return view('teachers.edit', compact('teacher', 'subjects', 'turmas'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'turmas' => 'nullable|array',
            'turmas.*' => 'exists:turmas,id',
        ]);

        DB::beginTransaction();

        try {
            // Atualizar atribuições
            if ($request->has('subjects')) {
                $teacher->subjects()->sync($request->subjects);
            }

            if ($request->has('turmas')) {
                $teacher->turmas()->sync($request->turmas);
            }

            // Atualizar usuário se necessário
            if ($request->has('name') || $request->has('email')) {
                $userData = $request->validate([
                    'name' => 'sometimes|string|max:255',
                    'email' => 'sometimes|string|email|max:255|unique:users,email,' . $teacher->user_id,
                ]);

                $teacher->user->update($userData);
            }

            DB::commit();

            return redirect()->route('teachers.show', $teacher)
                             ->with('success', 'Professor atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar professor: ' . $e->getMessage()]);
        }
    }

    public function destroy(Teacher $teacher)
    {
        DB::beginTransaction();

        try {
            // Remover atribuições primeiro
            $teacher->subjects()->detach();
            $teacher->turmas()->detach();

            // Excluir o usuário (isso excluirá o professor também por causa do cascade)
            $teacher->user->delete();

            DB::commit();

            return redirect()->route('teachers.index')
                             ->with('success', 'Professor excluído com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao excluir professor: ' . $e->getMessage()]);
        }
    }
}
