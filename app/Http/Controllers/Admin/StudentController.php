<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Turma;
use App\Models\Guardian;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Lista todos os alunos
     */
    public function index(Request $request)
    {
        $query = Student::with(['user', 'turma', 'guardians.user'])
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('registration_number', 'like', "%{$search}%");
        }

        if ($request->filled('turma_id')) {
            $query->where('turma_id', $request->turma_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->paginate(20);
        $turmas = Turma::all();
        $stats = $this->getStats();

        return view('admin.students.index', compact('students', 'turmas', 'stats'));
    }

    /**
     * Mostra formulário de criação
     */
    public function create()
    {
        $turmas = Turma::where('status', 'active')->get();
        $guardians = Guardian::with('user')->get();
        $stats = $this->getStats();

        return view('admin.students.create', compact('turmas', 'guardians', 'stats'));
    }

    /**
     * Salva novo aluno
     */
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

            // Dados específicos do aluno
            'registration_number' => 'required|string|max:50|unique:students',
            'identity_document' => 'nullable|string|max:50',
            'turma_id' => 'required|exists:turmas,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,inactive,graduated,transferred',

            // Responsáveis
            'guardian_ids' => 'nullable|array',
            'guardian_ids.*' => 'exists:guardians,id',
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
                'emergency_contact' => $request->emergency_contact,
                'status' => 'approved',
                'email_verified_at' => now(),
                'approved_at' => now(),
            ]);

            // Atribui role de estudante
            $user->assignRole('student');

            // Cria o perfil de estudante
            $student = Student::create([
                'user_id' => $user->id,
                'registration_number' => $request->registration_number,
                'identity_document' => $request->identity_document,
                'turma_id' => $request->turma_id,
                'enrollment_date' => $request->enrollment_date,
                'status' => $request->status,
            ]);

            // Vincula responsáveis
            if ($request->filled('guardian_ids')) {
                $student->guardians()->sync($request->guardian_ids);
            }

            DB::commit();

            return redirect()->route('admin.students.show', $student)
                ->with('success', 'Aluno criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao criar aluno: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostra detalhes do aluno
     */
    public function show(Student $student)
    {
        $student->load(['user', 'turma', 'guardians.user', 'grades.subject']);
        $guardians = Guardian::all();
        $stats = $this->getStats();

        return view('admin.students.show', compact('student', 'guardians', 'stats'));
    }

    /**
     * Mostra formulário de edição
     */
    public function edit(Student $student)
    {
        $student->load(['user', 'guardians']);
        $turmas = Turma::where('status', 'active')->get();
        $guardians = Guardian::with('user')->get();
        $stats = $this->getStats();

        return view('admin.students.edit', compact('student', 'turmas', 'guardians', 'stats'));
    }

    /**
     * Atualiza aluno
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($student->user_id)
            ],
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'address' => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:255',

            // Dados específicos do aluno
            'registration_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students')->ignore($student->id)
            ],
            'identity_document' => 'nullable|string|max:50',
            'turma_id' => 'required|exists:turmas,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,inactive,graduated,transferred',

            // Responsáveis
            'guardian_ids' => 'nullable|array',
            'guardian_ids.*' => 'exists:guardians,id',
        ]);

        DB::beginTransaction();

        try {
            // Atualiza dados do usuário
            $student->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'emergency_contact' => $request->emergency_contact,
            ]);

            // Atualiza dados do aluno
            $student->update([
                'registration_number' => $request->registration_number,
                'identity_document' => $request->identity_document,
                'turma_id' => $request->turma_id,
                'enrollment_date' => $request->enrollment_date,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Atualiza responsáveis
            $student->guardians()->sync($request->guardian_ids ?? []);

            DB::commit();

            return redirect()->route('admin.students.show', $student)
                ->with('success', 'Aluno atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao atualizar aluno: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove aluno
     */
    public function destroy(Student $student)
    {
        DB::beginTransaction();

        try {
            // Remove vínculos com responsáveis
            $student->guardians()->detach();

            // Remove o aluno
            $student->delete();

            // Remove o usuário associado (opcional - depende da regra de negócio)
            // $student->user->delete();

            DB::commit();

            return redirect()->route('admin.students.index')
                ->with('success', 'Aluno removido com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao remover aluno: ' . $e->getMessage());
        }
    }

    /**
     * Exporta alunos
     */
    public function export(Request $request)
    {
        // Implementar exportação CSV/Excel
        return response()->json(['message' => 'Exportação em desenvolvimento']);
    }

    /**
     * Importa alunos
     */
    public function import(Request $request)
    {
        // Implementar importação CSV/Excel
        return response()->json(['message' => 'Importação em desenvolvimento']);
    }

    /**
     * Ações em massa
     */
    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,inactivate,delete,change_turma',
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
            'turma_id' => 'nullable|required_if:action,change_turma|exists:turmas,id',
        ]);

        $students = Student::whereIn('id', $request->students)->get();

        DB::beginTransaction();

        try {
            foreach ($students as $student) {
                switch ($request->action) {
                    case 'activate':
                        $student->update(['status' => 'active']);
                        break;
                    case 'inactivate':
                        $student->update(['status' => 'inactive']);
                        break;
                    case 'change_turma':
                        $student->update(['turma_id' => $request->turma_id]);
                        break;
                    case 'delete':
                        $student->delete();
                        break;
                }
            }

            DB::commit();

            $message = match ($request->action) {
                'activate' => 'Alunos ativados com sucesso!',
                'inactivate' => 'Alunos inativados com sucesso!',
                'change_turma' => 'Turma alterada com sucesso!',
                'delete' => 'Alunos removidos com sucesso!',
            };

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao processar ação em massa: ' . $e->getMessage());
        }
    }

    /**
     * Vincula responsável ao aluno
     */
    public function attachGuardian(Request $request, Student $student)
    {
        $request->validate([
            'guardian_id' => 'required|exists:guardians,id',
            'relationship' => 'nullable|string|max:100',
        ]);

        $student->guardians()->attach($request->guardian_id, [
            'relationship' => $request->relationship
        ]);

        return redirect()->back()
            ->with('success', 'Responsável vinculado com sucesso!');
    }

    /**
     * Remove vínculo com responsável
     */
    public function detachGuardian(Student $student, Guardian $guardian)
    {
        $student->guardians()->detach($guardian->id);

        return redirect()->back()
            ->with('success', 'Responsável removido com sucesso!');
    }

    private function getStats()
    {
        return [
            'total' => User::count(),
            'pending_users' => User::where('status', 'pending')->count(),
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_turmas' => Turma::count(),
            'approved' => User::where('status', 'approved')->count(),
            'pending' => User::where('status', 'pending')->count(),
            'rejected' => User::where('status', 'rejected')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'recent_registrations' => User::with('roles')->latest()->take(10)->get(),
            'by_role' => [
                'student' => User::whereHas('roles', fn($q) => $q->where('name', 'student'))->count(),
                'teacher' => User::whereHas('roles', fn($q) => $q->where('name', 'teacher'))->count(),
                'guardian' => User::whereHas('roles', fn($q) => $q->where('name', 'guardian'))->count(),
                'admin' => User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->count(),
                'director' => User::whereHas('roles', fn($q) => $q->where('name', 'director'))->count(),
            ]
        ];
    }
}
