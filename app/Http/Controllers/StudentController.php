<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Turma;
use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'approved']);
        $this->middleware('role:admin|director')->except(['show']);
    }

    public function index(Request $request)
    {
        $query = Student::with(['user', 'turma', 'guardians.user']);

        // Filtros
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            })->orWhere('identity_document', 'like', "%{$search}%")
              ->orWhere('registration_number', 'like', "%{$search}%");
        }

        if ($request->has('turma_id')) {
            $query->where('turma_id', $request->turma_id);
        }

        if ($request->has('status')) {
            if ($request->status == 'with_turma') {
                $query->whereNotNull('turma_id');
            } elseif ($request->status == 'without_turma') {
                $query->whereNull('turma_id');
            }
        }

        $students = $query->latest()->paginate(20);

        // Estatísticas
        $totalStudents = Student::count();
        $activeStudents = Student::whereHas('user', function($q) {
            $q->where('status', 'approved');
        })->count();
        $pendingStudents = Student::whereNull('turma_id')->count();
        $turmas = Turma::all();
        $totalTurmas = $turmas->count();

        return view('students.index', compact('students', 'turmas', 'totalStudents', 'activeStudents', 'pendingStudents', 'totalTurmas'));
    }

    public function create()
    {
        $turmas = Turma::all();
        $guardians = Guardian::with('user')->get();

        return view('students.create', compact('turmas', 'guardians'));
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
            'identity_document' => 'required|string|max:50|unique:students',
            'registration_number' => 'nullable|string|max:50|unique:students',
            'turma_id' => 'nullable|exists:turmas,id',
            'guardian_ids' => 'nullable|array',
            'guardian_ids.*' => 'exists:guardians,id',
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

            // Atribuir role de student
            $user->assignRole('student');

            // Criar perfil de estudante
            $student = Student::create([
                'user_id' => $user->id,
                'identity_document' => $request->identity_document,
                'registration_number' => $request->registration_number,
                'turma_id' => $request->turma_id,
            ]);

            // Vincular responsáveis
            if ($request->has('guardian_ids')) {
                $student->guardians()->sync($request->guardian_ids);
            }

            DB::commit();

            return redirect()->route('students.show', $student)
                             ->with('success', 'Aluno criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erro ao criar aluno: ' . $e->getMessage()]);
        }
    }

    public function show(Student $student)
    {
        $this->authorize('view', $student);

        $student->load(['user', 'turma', 'guardians.user', 'grades.subject', 'absences.subject']);

        // Calcular estatísticas
        $averageGrade = $student->grades()->avg('value') ?? 0;
        $totalAbsences = $student->absences()->count();
        $justifiedAbsences = $student->absences()->where('justified', true)->count();

        return view('students.show', compact('student', 'averageGrade', 'totalAbsences', 'justifiedAbsences'));
    }

    public function edit(Student $student)
    {
        $turmas = Turma::all();
        $guardians = Guardian::with('user')->get();
        $student->load('guardians');

        return view('students.edit', compact('student', 'turmas', 'guardians'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'identity_document' => 'sometimes|string|max:50|unique:students,identity_document,' . $student->id,
            'registration_number' => 'nullable|string|max:50|unique:students,registration_number,' . $student->id,
            'turma_id' => 'nullable|exists:turmas,id',
            'guardian_ids' => 'nullable|array',
            'guardian_ids.*' => 'exists:guardians,id',
        ]);

        DB::beginTransaction();

        try {
            // Atualizar estudante
            $student->update([
                'identity_document' => $request->identity_document ?? $student->identity_document,
                'registration_number' => $request->registration_number,
                'turma_id' => $request->turma_id,
            ]);

            // Atualizar usuário se necessário
            if ($request->has('name') || $request->has('email')) {
                $userData = $request->validate([
                    'name' => 'sometimes|string|max:255',
                    'email' => 'sometimes|string|email|max:255|unique:users,email,' . $student->user_id,
                ]);

                $student->user->update($userData);
            }

            // Atualizar responsáveis
            if ($request->has('guardian_ids')) {
                $student->guardians()->sync($request->guardian_ids);
            }

            DB::commit();

            return redirect()->route('students.show', $student)
                             ->with('success', 'Aluno atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar aluno: ' . $e->getMessage()]);
        }
    }

    public function destroy(Student $student)
    {
        DB::beginTransaction();

        try {
            // Excluir o usuário (isso excluirá o estudante também por causa do cascade)
            $student->user->delete();

            DB::commit();

            return redirect()->route('students.index')
                             ->with('success', 'Aluno excluído com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao excluir aluno: ' . $e->getMessage()]);
        }
    }
}
