<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Guardian;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            // Validação básica para todos os usuários
            $basicRules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', 'in:student,teacher,guardian'],
                'phone' => ['required', 'string', 'max:20'],
                'birth_date' => ['required', 'date'],
                'address' => ['required', 'string', 'max:500'],
                'gender' => ['nullable', 'in:male,female,other,prefer_not_to_say'],
            ];

            // Validação específica por role
            $roleRules = [];

            if ($request->role === 'student') {
                $roleRules = [
                    'identity_document' => ['required', 'string', 'max:50', 'unique:students,identity_document'],
                    'registration_number' => ['nullable', 'string', 'max:50', 'unique:students,registration_number'],
                ];
            } elseif ($request->role === 'guardian') {
                $roleRules = [
                    'emergency_contact' => ['nullable', 'string', 'max:20'],
                ];
            }

            // Combinar e validar regras
            $rules = array_merge($basicRules, $roleRules);
            $validated = $request->validate($rules);

            // Validar idade mínima (6 anos)
            $birthDate = new \DateTime($validated['birth_date']);
            $today = new \DateTime();
            $age = $today->diff($birthDate)->y;

            if ($age < 6) {
                throw ValidationException::withMessages([
                    'birth_date' => 'Você deve ter pelo menos 6 anos para se registrar.'
                ]);
            }

            // Verificar se o email já existe (dupla verificação)
            if (User::where('email', $validated['email'])->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'Este email já está registrado.'
                ]);
            }

            // Verificar campos únicos específicos por role
            if ($validated['role'] === 'student') {
                if (Student::where('identity_document', $validated['identity_document'])->exists()) {
                    throw ValidationException::withMessages([
                        'identity_document' => 'Este documento já está registrado.'
                    ]);
                }

                if (isset($validated['registration_number']) &&
                    Student::where('registration_number', $validated['registration_number'])->exists()) {
                    throw ValidationException::withMessages([
                        'registration_number' => 'Este número de matrícula já está em uso.'
                    ]);
                }
            }

            // Iniciar transação para garantir consistência
            \DB::beginTransaction();

            try {
                // Criar usuário com status pendente
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'phone' => $validated['phone'],
                    'birth_date' => $validated['birth_date'],
                    'address' => $validated['address'],
                    'gender' => $validated['gender'] ?? null,
                    'status' => 'pending',
                    'is_approved' => false,
                    'emergency_contact' => $validated['emergency_contact'] ?? null,
                ]);

                // Atribuir role ao usuário
                $user->assignRole($validated['role']);

                // Criar perfil específico baseado no role
                switch ($validated['role']) {
                    case 'student':
                        Student::create([
                            'user_id' => $user->id,
                            'identity_document' => $validated['identity_document'],
                            'registration_number' => $validated['registration_number'] ?? null,
                            'turma_id' => null,
                        ]);
                        break;

                    case 'teacher':
                        Teacher::create([
                            'user_id' => $user->id,
                        ]);
                        break;

                    case 'guardian':
                        Guardian::create([
                            'user_id' => $user->id,
                        ]);
                        break;
                }

                // Commit da transação
                \DB::commit();

                event(new Registered($user));

                // Não faz login automático - aguarda aprovação
                Auth::login($user);

                return redirect()->route('register.success')
                                 ->with('success', 'Registro realizado com sucesso! Sua conta está aguardando aprovação.');

            } catch (QueryException $e) {
                \DB::rollBack();

                // Tratar erros específicos do banco de dados
                $errorCode = $e->errorInfo[1];

                if ($errorCode == 1062) { // MySQL duplicate entry
                    $message = $this->getDuplicateErrorMessage($e->getMessage());
                    throw ValidationException::withMessages([
                        'email' => $message
                    ]);
                }

                throw $e;
            }

        } catch (ValidationException $e) {
            // Capturar erros de validação e redirecionar de volta com erros
            throw $e;

        } catch (\Exception $e) {
            // Capturar outros erros
            \Log::error('Erro no registro: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);

            return back()->withInput()
                         ->withErrors([
                             'general' => 'Ocorreu um erro ao processar seu registro. Por favor, tente novamente.'
                         ]);
        }
    }

    /**
     * Extrair mensagem de erro amigável para duplicatas
     */
    private function getDuplicateErrorMessage(string $errorMessage): string
    {
        if (str_contains($errorMessage, 'users_email_unique')) {
            return 'Este email já está registrado.';
        }

        if (str_contains($errorMessage, 'students_identity_document_unique')) {
            return 'Este documento de identificação já está registrado.';
        }

        if (str_contains($errorMessage, 'students_registration_number_unique')) {
            return 'Este número de matrícula já está em uso.';
        }

        return 'Os dados informados já estão em uso.';
    }
}
