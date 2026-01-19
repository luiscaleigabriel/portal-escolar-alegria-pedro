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

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validação dos dados
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,teacher,guardian'],
            'phone' => ['required', 'string', 'max:20'],
            'birth_date' => ['required', 'date'],
            'address' => ['required', 'string', 'max:500'],

            // Campos específicos por role
            'identity_document' => ['required_if:role,student', 'string', 'max:50'],
            'registration_number' => ['nullable', 'string', 'max:50'],
        ]);

        // Criar usuário com status pendente
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'birth_date' => $validated['birth_date'],
            'address' => $validated['address'],
            'status' => 'pending',
            'is_approved' => false,
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
                    // Turma será atribuída posteriormente pelo admin
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

        event(new Registered($user));

        // NOTA: NÃO fazemos login automático!
        // O usuário precisa ser aprovado primeiro

        return redirect()->route('register.success')
                         ->with('success', 'Registro realizado com sucesso! Sua conta está aguardando aprovação.');
    }
}
