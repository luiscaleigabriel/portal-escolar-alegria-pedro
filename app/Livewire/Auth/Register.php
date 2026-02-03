<?php

namespace App\Livewire\Auth;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class Register extends Component
{
    public $step = 1;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $role = 'student';
    public $birth_date;
    public $address;

    // Campos específicos para aluno
    public $student_number;

    // Campos específicos para responsável
    public $student_email; // email do aluno para vincular
    public $relationship = 'parent';

    // Campos específicos para professor
    public $qualification;
    public $subjects = [];

    protected $rulesStep1 = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'required|string|min:9|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:student,teacher,parent',
    ];

    protected $rulesStep2 = [
        'birth_date' => 'required|date|before:today',
        'address' => 'required|string|max:500',
    ];

    protected $rulesStep3 = [];

    public function mount()
    {
        // Inicializar regras do passo 3 baseadas no role
        $this->setStep3Rules();
    }

    public function updatedRole($value)
    {
        $this->setStep3Rules();
    }

    private function setStep3Rules()
    {
        $this->rulesStep3 = match($this->role) {
            'student' => [
                'student_number' => 'required|string|unique:users,student_number',
            ],
            'parent' => [
                'student_email' => 'required|email|exists:users,email',
                'relationship' => 'required|in:father,mother,guardian,other',
            ],
            'teacher' => [
                'qualification' => 'required|string|max:255',
                'subjects' => 'required|array|min:1',
            ],
            default => [],
        };
    }

    public function nextStep()
    {
        if ($this->step == 1) {
            $this->validate($this->rulesStep1);
        } elseif ($this->step == 2) {
            $this->validate($this->rulesStep2);
        }

        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function register()
    {
        $this->validate($this->rulesStep3);

        // Criar usuário
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'birth_date' => $this->birth_date,
            'address' => $this->address,
            'is_active' => false,
            'is_approved' => false,
        ]);

        // Adicionar campos específicos
        if ($this->role === 'student') {
            $user->student_number = $this->student_number;
        } elseif ($this->role === 'teacher') {
            $user->qualification = $this->qualification;
            // Os subjects serão atribuídos depois da aprovação pela secretaria
        }

        $user->save();

        // Vincular responsável ao aluno
        if ($this->role === 'parent') {
            $student = User::where('email', $this->student_email)->first();
            if ($student) {
                $user->children()->attach($student->id, [
                    'relationship' => $this->relationship
                ]);
            }
        }

        // Disparar evento de registro
        event(new UserRegistered($user));

        // Mostrar mensagem de sucesso
        session()->flash('message', 'Sua inscrição foi enviada com sucesso! Dirija se a secretaria com o seu cartão ou comprovativo!. Você receberá um email quando sua conta for aprovada.');

        // Redirecionar para login
        return redirect()->route('login');
    }

    public function render()
    {
        $subjectsList = \App\Models\Subject::all()->pluck('name', 'id')->toArray();

        return view('livewire.auth.register', [
            'subjectsList' => $subjectsList
        ])->layout('layouts.guest');
    }
}
