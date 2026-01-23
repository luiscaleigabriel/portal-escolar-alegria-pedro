<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Turma;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with(['user', 'subjects', 'turmas'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $teachers = $query->paginate(20);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $subjects = Subject::where('status', 'active')->get();
        $turmas = Turma::where('status', 'active')->get();

        return view('admin.teachers.create', compact('subjects', 'turmas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'address' => 'nullable|string|max:500',

            // Dados específicos do professor
            'academic_degree' => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:200',
            'hire_date' => 'required|date',
            'status' => 'required|in:active,inactive,on_leave',

            // Turmas e disciplinas
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
            'turma_ids' => 'nullable|array',
            'turma_ids.*' => 'exists:turmas,id',
        ]);

        DB::beginTransaction();

        try {
            // Cria o usuário
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'status' => 'approved',
                'email_verified_at' => now(),
                'approved_at' => now(),
                'approver_id' => auth()->id(),
            ]);

            // Atribui role de professor
            $user->assignRole('teacher');

            // Cria o perfil de professor
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'academic_degree' => $request->academic_degree,
                'specialization' => $request->specialization,
                'hire_date' => $request->hire_date,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Vincula disciplinas
            if ($request->filled('subject_ids')) {
                $teacher->subjects()->sync($request->subject_ids);
            }

            // Vincula turmas
            if ($request->filled('turma_ids')) {
                $teacher->turmas()->sync($request->turma_ids);
            }

            DB::commit();

            return redirect()->route('admin.teachers.show', $teacher)
                ->with('success', 'Professor criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao criar professor: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'subjects', 'turmas.students.user']);

        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load(['user', 'subjects', 'turmas']);
        $subjects = Subject::where('status', 'active')->get();
        $turmas = Turma::where('status', 'active')->get();

        return view('admin.teachers.edit', compact('teacher', 'subjects', 'turmas'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($teacher->user_id)
            ],
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'address' => 'nullable|string|max:500',

            // Dados específicos do professor
            'academic_degree' => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:200',
            'hire_date' => 'required|date',
            'status' => 'required|in:active,inactive,on_leave',

            // Turmas e disciplinas
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
            'turma_ids' => 'nullable|array',
            'turma_ids.*' => 'exists:turmas,id',
        ]);

        DB::beginTransaction();

        try {
            // Atualiza dados do usuário
            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
            ]);

            // Atualiza dados do professor
            $teacher->update([
                'academic_degree' => $request->academic_degree,
                'specialization' => $request->specialization,
                'hire_date' => $request->hire_date,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Atualiza disciplinas
            $teacher->subjects()->sync($request->subject_ids ?? []);

            // Atualiza turmas
            $teacher->turmas()->sync($request->turma_ids ?? []);

            DB::commit();

            return redirect()->route('admin.teachers.show', $teacher)
                ->with('success', 'Professor atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao atualizar professor: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Teacher $teacher)
    {
        DB::beginTransaction();

        try {
            // Remove vínculos
            $teacher->subjects()->detach();
            $teacher->turmas()->detach();

            // Remove o professor
            $teacher->delete();

            // Remove o usuário associado (opcional)
            // $teacher->user->delete();

            DB::commit();

            return redirect()->route('admin.teachers.index')
                ->with('success', 'Professor removido com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao remover professor: ' . $e->getMessage());
        }
    }
}
