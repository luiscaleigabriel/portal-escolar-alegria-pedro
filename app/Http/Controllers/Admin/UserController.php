<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Guardian;
use App\Models\Turma;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Lista todos os usuários
     */
    public function index(Request $request)
    {
        $query = User::with('roles')->latest();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('verified')) {
            if ($request->verified == 'yes') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Paginação
        $users = $query->paginate(20)->withQueryString();

        // Estatísticas
        $stats = $this->getStats();

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'stats', 'roles'));
    }

    /**
     * Mostra formulário de criação
     */
    public function create()
    {
        $roles = Role::whereNotIn('name', ['admin'])->get();
        $turmas = Turma::active()->get();

        return view('admin.users.create', compact('roles', 'turmas'));
    }

    /**
     * Salva novo usuário
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other,prefer_not_to_say'],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'exists:roles,name'],
            'status' => ['required', 'in:pending,approved'],

            // Campos específicos para estudantes
            'registration_number' => ['nullable', 'string', 'max:50'],
            'identity_document' => ['nullable', 'string', 'max:50'],
            'turma_id' => ['nullable', 'exists:turmas,id'],

            // Campos para responsáveis
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

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
                'status' => $request->status,
                'email_verified_at' => $request->status === 'approved' ? now() : null,
                'approved_at' => $request->status === 'approved' ? now() : null,
                'approver_id' => $request->status === 'approved' ? auth()->id() : null,
            ]);

            // Atribui role
            $user->assignRole($request->role);

            // Cria perfil específico baseado no role
            switch ($request->role) {
                case 'student':
                    $profile = Student::create([
                        'user_id' => $user->id,
                        'registration_number' => $request->registration_number,
                        'identity_document' => $request->identity_document,
                        'turma_id' => $request->turma_id,
                    ]);
                    break;

                case 'teacher':
                    $profile = Teacher::create([
                        'user_id' => $user->id,
                    ]);
                    break;

                case 'guardian':
                    $profile = Guardian::create([
                        'user_id' => $user->id,
                    ]);

                    // Vincula alunos se fornecidos
                    if ($request->filled('student_ids')) {
                        $profile->students()->attach($request->student_ids);
                    }
                    break;
            }

            DB::commit();

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'Usuário criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao criar usuário: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostra detalhes do usuário
     */
    public function show(User $user)
    {
        $user->load('roles', 'approver');

        $stats = $this->getStats();

        // Carrega perfil específico
        $profile = null;
        if ($user->hasRole('student')) {
            $profile = Student::with('turma', 'guardians.user')->where('user_id', $user->id)->first();
        } elseif ($user->hasRole('teacher')) {
            $profile = Teacher::where('user_id', $user->id)->first();
        } elseif ($user->hasRole('guardian')) {
            $profile = Guardian::with('students.user', 'students.turma')->where('user_id', $user->id)->first();
        }

        return view('admin.users.show', compact('user', 'profile', 'stats'));
    }

    /**
     * Mostra formulário de edição
     */
    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::whereNotIn('name', ['admin'])->get();
        $turmas = Turma::active()->get();
        $stats = $this->getStats();

        // Carrega perfil específico
        $profile = null;
        $students = collect();

        if ($user->hasRole('student')) {
            $profile = Student::with('turma')->where('user_id', $user->id)->first();
        } elseif ($user->hasRole('guardian')) {
            $profile = Guardian::with('students')->where('user_id', $user->id)->first();
            $students = Student::with('user')->get();
        }

        return view('admin.users.edit', compact('user', 'roles', 'turmas', 'profile', 'students', 'stats'));
    }

    /**
     * Atualiza usuário
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other,prefer_not_to_say'],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:pending,approved,rejected,suspended'],

            // Campos específicos para estudantes
            'registration_number' => ['nullable', 'string', 'max:50'],
            'identity_document' => ['nullable', 'string', 'max:50'],
            'turma_id' => ['nullable', 'exists:turmas,id'],

            // Campos para responsáveis
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Atualiza dados básicos do usuário
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'emergency_contact' => $request->emergency_contact,
                'status' => $request->status,
            ];

            // Se mudou para aprovado, marca como verificado
            if ($request->status === 'approved' && $user->status !== 'approved') {
                $updateData['email_verified_at'] = now();
                $updateData['approved_at'] = now();
                $updateData['approver_id'] = auth()->id();
            }

            // Se mudou para rejeitado ou suspenso, remove aprovação
            if (in_array($request->status, ['rejected', 'suspended']) && $user->status === 'approved') {
                $updateData['approved_at'] = null;
                $updateData['approver_id'] = null;
            }

            $user->update($updateData);

            // Atualiza perfil específico
            if ($user->hasRole('student')) {
                Student::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'registration_number' => $request->registration_number,
                        'identity_document' => $request->identity_document,
                        'turma_id' => $request->turma_id,
                    ]
                );
            } elseif ($user->hasRole('guardian')) {
                $guardian = Guardian::firstOrCreate(['user_id' => $user->id]);
                if ($request->filled('student_ids')) {
                    $guardian->students()->sync($request->student_ids);
                } else {
                    $guardian->students()->detach();
                }
            }

            DB::commit();

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove usuário
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();

        try {
            // Remove perfil específico primeiro
            if ($user->hasRole('student')) {
                Student::where('user_id', $user->id)->delete();
            } elseif ($user->hasRole('teacher')) {
                Teacher::where('user_id', $user->id)->delete();
            } elseif ($user->hasRole('guardian')) {
                Guardian::where('user_id', $user->id)->delete();
            }

            // Remove o usuário
            $user->delete();

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'Usuário removido com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao remover usuário: ' . $e->getMessage());
        }
    }

    /**
     * Aprova usuário
     */
    public function approve(Request $request, User $user)
    {
        $user->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approver_id' => auth()->id(),
            'email_verified_at' => now(),
        ]);

        // TODO: Enviar email de aprovação

        return redirect()->back()
            ->with('success', 'Usuário aprovado com sucesso!');
    }

    /**
     * Rejeita usuário
     */
    public function reject(Request $request, User $user)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'approved_at' => null,
            'approver_id' => null,
        ]);

        // TODO: Enviar email de rejeição

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário rejeitado com sucesso!');
    }

    /**
     * Suspende usuário
     */
    public function suspend(Request $request, User $user)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user->update([
            'status' => 'suspended',
            'suspension_reason' => $request->reason,
            'suspended_at' => now(),
            'suspended_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Usuário suspenso com sucesso!');
    }

    /**
     * Ativa usuário suspenso
     */
    public function activate(Request $request, User $user)
    {
        $user->update([
            'status' => 'approved',
            'suspension_reason' => null,
            'suspended_at' => null,
            'suspended_by' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Usuário ativado com sucesso!');
    }

    /**
     * Ações em massa
     */
    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => ['required', 'in:approve,reject,suspend,delete'],
            'users' => ['required', 'array'],
            'users.*' => ['exists:users,id'],
            'reason' => ['nullable', 'required_if:action,reject,suspend', 'string', 'max:1000'],
        ]);

        $users = User::whereIn('id', $request->users)->get();

        DB::beginTransaction();

        try {
            foreach ($users as $user) {
                switch ($request->action) {
                    case 'approve':
                        $user->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                            'approver_id' => auth()->id(),
                            'email_verified_at' => now(),
                        ]);
                        break;

                    case 'reject':
                        $user->update([
                            'status' => 'rejected',
                            'rejection_reason' => $request->reason,
                            'approved_at' => null,
                            'approver_id' => null,
                        ]);
                        break;

                    case 'suspend':
                        $user->update([
                            'status' => 'suspended',
                            'suspension_reason' => $request->reason,
                            'suspended_at' => now(),
                            'suspended_by' => auth()->id(),
                        ]);
                        break;

                    case 'delete':
                        // Remove perfil específico primeiro
                        if ($user->hasRole('student')) {
                            Student::where('user_id', $user->id)->delete();
                        } elseif ($user->hasRole('teacher')) {
                            Teacher::where('user_id', $user->id)->delete();
                        } elseif ($user->hasRole('guardian')) {
                            Guardian::where('user_id', $user->id)->delete();
                        }
                        $user->delete();
                        break;
                }
            }

            DB::commit();

            $message = match($request->action) {
                'approve' => 'Usuários aprovados com sucesso!',
                'reject' => 'Usuários rejeitados com sucesso!',
                'suspend' => 'Usuários suspensos com sucesso!',
                'delete' => 'Usuários removidos com sucesso!',
            };

            return redirect()->back()
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao processar ação em massa: ' . $e->getMessage());
        }
    }

    /**
     * Importa usuários
     */
    public function import(Request $request)
    {
        // Implementar lógica de importação CSV/Excel
        return redirect()->back()
            ->with('warning', 'Funcionalidade de importação em desenvolvimento.');
    }

    /**
     * Exporta usuários
     */
    public function export(Request $request)
    {
        // Implementar lógica de exportação CSV/Excel
        return redirect()->back()
            ->with('warning', 'Funcionalidade de exportação em desenvolvimento.');
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
