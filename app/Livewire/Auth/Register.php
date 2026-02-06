<?php

namespace App\Livewire\Auth;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $step = 1;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $role = '';
    public $birth_date;
    public $address;
    public $gender;
    public $nationality = 'Angolana';
    public $id_number;
    public $accept_terms = false;

    // Campos específicos para aluno
    public $student_number;
    public $academic_year;
    public $course_area;

    // Campos específicos para responsável
    public $student_email;
    public $relationship = '';
    public $parent_notes;
    public $updatedRole;
    // Campos específicos para professor
    public $qualification;
    public $specializations = [];
    public $experience_years = 0;

    // Regras dinâmicas baseadas no passo
    public function getRules()
    {
        $rules = [];

        // Regras para passo 1
        if ($this->step == 1) {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|min:9|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:student,teacher,parent',
            ];
        }

        // Regras para passo 2
        if ($this->step == 2) {
            $rules = [
                'birth_date' => 'required|date|before:today',
                'address' => 'required|string|max:500',
                'id_number' => 'required|string|max:50|unique:users,id_number',
            ];

            // Campos opcionais no passo 2
            if ($this->gender) {
                $rules['gender'] = 'in:male,female,other';
            }
            if ($this->nationality) {
                $rules['nationality'] = 'string|max:100';
            }
        }

        // Regras para passo 3
        if ($this->step == 3) {
            $rules = [
                'accept_terms' => 'required|accepted',
            ];

            // Regras específicas por role
            if ($this->role === 'student') {
                $rules['student_number'] = 'required|string|unique:users,student_number';

                // Campos opcionais para aluno
                if ($this->academic_year) {
                    $rules['academic_year'] = 'integer|min:' . (date('Y') - 5) . '|max:' . (date('Y') + 5);
                }
                if ($this->course_area) {
                    $rules['course_area'] = 'string|max:255';
                }
            }
            elseif ($this->role === 'teacher') {
                $rules['qualification'] = 'required|string|max:255';
                $rules['specializations'] = 'required|array|min:1';

                // Campos opcionais para professor
                if ($this->experience_years) {
                    $rules['experience_years'] = 'integer|min:0|max:50';
                }
            }
            elseif ($this->role === 'parent') {
                $rules['student_email'] = 'required|email|exists:users,email';
                $rules['relationship'] = 'required|in:father,mother,guardian,other';

                // Campo opcional para responsável
                if ($this->parent_notes) {
                    $rules['parent_notes'] = 'string|max:500';
                }
            }
        }

        return $rules;
    }

    protected $messages = [
        'accept_terms.required' => 'Você deve aceitar os termos e condições.',
        'accept_terms.accepted' => 'Você deve aceitar os termos e condições.',
        'student_email.exists' => 'O email do estudante não existe no sistema.',
        'id_number.unique' => 'Este número de documento já está registado.',
        'student_number.unique' => 'Este número de estudante já está registado.',
        'birth_date.before' => 'A data de nascimento deve ser anterior a hoje.',
        'password.confirmed' => 'As senhas não coincidem.',
        'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
    ];

    // Sobrescrever o método validate para usar regras dinâmicas
    public function validate($rules = null, $messages = [], $attributes = [])
    {
        $rules = $this->getRules();
        parent::validate($rules, $messages, $attributes);
    }

    public function updated($propertyName)
    {
        // Validar apenas se o campo pertence ao passo atual
        if ($this->shouldValidateField($propertyName)) {
            $this->validateOnly($propertyName);
        }
    }

    private function shouldValidateField($field)
    {
        // Mapear campos para seus passos
        $fieldSteps = [
            // Passo 1
            'name' => 1,
            'email' => 1,
            'phone' => 1,
            'password' => 1,
            'password_confirmation' => 1,
            'role' => 1,

            // Passo 2
            'birth_date' => 2,
            'address' => 2,
            'gender' => 2,
            'nationality' => 2,
            'id_number' => 2,

            // Passo 3
            'accept_terms' => 3,
            'student_number' => 3,
            'academic_year' => 3,
            'course_area' => 3,
            'student_email' => 3,
            'relationship' => 3,
            'parent_notes' => 3,
            'qualification' => 3,
            'specializations' => 3,
            'experience_years' => 3,
        ];

        return isset($fieldSteps[$field]) && $fieldSteps[$field] == $this->step;
    }

    public function nextStep()
    {
        // Validar apenas os campos do passo atual
        $this->validate();

        // Validações adicionais
        if ($this->step == 2 && $this->role === 'parent') {
            // Verificar se o estudante existe
            $student = User::where('email', $this->student_email)
                ->where('role', 'student')
                ->first();

            if (!$student) {
                $this->addError('student_email', 'Estudante não encontrado ou email inválido.');
                return;
            }
        }

        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;

        // Limpar erros quando voltar
        $this->resetErrorBag();
    }

    public function register()
    {
        // Validar apenas os campos do passo 3
        $this->validate();

        // Validação adicional para responsável
        if ($this->role === 'parent') {
            $student = User::where('email', $this->student_email)
                ->where('role', 'student')
                ->first();

            if (!$student) {
                $this->addError('student_email', 'Estudante não encontrado. Verifique o email.');
                return;
            }
        }

        // Criar usuário
        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'birth_date' => $this->birth_date,
            'address' => $this->address,
            'is_active' => false,
            'is_approved' => false,
        ];

        // Adicionar campos opcionais se existirem
        if ($this->gender) {
            $userData['gender'] = $this->gender;
        }
        if ($this->nationality) {
            $userData['nationality'] = $this->nationality;
        }
        if ($this->id_number) {
            $userData['id_number'] = $this->id_number;
        }

        $user = User::create($userData);

        // Adicionar campos específicos
        if ($this->role === 'student') {
            $user->student_number = $this->student_number;
            if ($this->academic_year) {
                $user->academic_year = $this->academic_year;
            }
            if ($this->course_area) {
                $user->course_area = $this->course_area;
            }
        }
        elseif ($this->role === 'teacher') {
            $user->qualification = $this->qualification;
            $user->specializations = json_encode($this->specializations);
            if ($this->experience_years) {
                $user->experience_years = $this->experience_years;
            }
        }

        $user->save();

        // Vincular responsável ao aluno
        if ($this->role === 'parent') {
            $student = User::where('email', $this->student_email)
                ->where('role', 'student')
                ->first();

            if ($student) {
                $user->children()->attach($student->id, [
                    'relationship' => $this->relationship,
                    'notes' => $this->parent_notes ?? null
                ]);
            }
        }

        // Disparar evento de registro
        event(new UserRegistered($user));

        // Mostrar mensagem de sucesso
        session()->flash('message', '✅ Sua inscrição foi enviada com sucesso! Aguarde a aprovação da secretaria. Você receberá um email quando sua conta for aprovada.');

        // Limpar formulário
        $this->resetForm();

        // Redirecionar para login
        return redirect()->route('login');
    }

    private function resetForm()
    {
        $this->reset([
            'step', 'name', 'email', 'phone', 'password', 'password_confirmation',
            'role', 'birth_date', 'address', 'gender', 'nationality', 'id_number',
            'accept_terms', 'student_number', 'academic_year', 'course_area',
            'student_email', 'relationship', 'parent_notes', 'qualification',
            'specializations', 'experience_years'
        ]);
        $this->resetErrorBag();
    }

    public function render()
    {
        // Definir ano académico padrão
        if (empty($this->academic_year)) {
            $this->academic_year = date('Y');
        }

        return view('livewire.auth.register')->layout('layouts.guest');
    }
}
