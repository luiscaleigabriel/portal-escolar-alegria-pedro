<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $phone;
    public $login_type = 'email';
    public $password;
    public $role = 'student';
    public $remember = false;

    protected $rules = [
        'password' => 'required|min:8',
        'role' => 'required|in:student,teacher,parent,secretary,admin',
        'remember' => 'boolean',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function login()
    {
        // Definir regras baseadas no tipo de login
        if ($this->login_type === 'email') {
            $this->rules['email'] = 'required|email';
        } else {
            $this->rules['phone'] = 'required|string|min:9';
        }

        $this->validate();

        // Verificar limite de tentativas
        $throttleKey = strtolower($this->loginInput()) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // Tentar autenticação
        $credentials = $this->getCredentials();

        if (!Auth::attempt($credentials, $this->remember)) {
            RateLimiter::hit($throttleKey);
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        // Verificar se o usuário está aprovado
        if (!$user->is_approved) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Sua conta ainda não foi aprovada. Aguarde a aprovação da secretaria.',
            ]);
        }

        // Verificar se o usuário está ativo
        if (!$user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Sua conta está desativada. Entre em contato com a administração.',
            ]);
        }

        // Verificar se o role corresponde
        if ($user->role !== $this->role) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Tipo de usuário incorreto. Selecione o perfil correto.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        // Redirecionar baseado no role
        return $this->redirectRoute($this->getDashboardRoute(), navigate: true);
    }

    private function loginInput()
    {
        return $this->login_type === 'email' ? $this->email : $this->phone;
    }

    private function getCredentials()
    {
        $credentials = ['password' => $this->password];

        if ($this->login_type === 'email') {
            $credentials['email'] = $this->email;
        } else {
            $credentials['phone'] = $this->phone;
        }

        return $credentials;
    }

    private function getDashboardRoute()
    {
        return match(Auth::user()->role) {
            'admin' => 'admin.dashboard',
            'secretary' => 'secretary.dashboard',
            'teacher' => 'teacher.dashboard',
            'student' => 'student.dashboard',
            'parent' => 'parent.dashboard',
            default => 'dashboard',
        };
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
