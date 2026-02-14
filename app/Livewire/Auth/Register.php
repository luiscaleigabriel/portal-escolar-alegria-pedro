<?php

namespace App\Livewire\Auth;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Register extends Component
{
    public $step = 1;

    // Step 1 - Informações Básicas
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = '';

    // Step 2 - Informações Pessoais
    public $birth_date = '';
    public $address = '';
    public $id_number = '';
    public $gender = '';
    public $nationality = 'Angolana';

    // Step 3 - Aceitação de Termos
    public $accept_terms = false;

    // Campos específicos para aluno
    public $student_number = '';
    public $academic_year = '';
    public $course_area = '';

    // Campos específicos para responsável
    public $student_email = '';
    public $relationship = '';
    public $parent_notes = '';

    // Campos específicos para professor
    public $qualification = '';
    public $specializations = [];
    public $experience_years = 0;

    // Controlar erros manualmente
    public $errors = [];

    protected function getStep1Rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|min:9|unique:users',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password',
            'role' => 'required|in:student,teacher,parent',
        ];
    }

    protected function getStep2Rules()
    {
        return [
            'birth_date' => 'required|date|before:today',
            'address' => 'required|string|max:500',
            'id_number' => 'required|string|max:50|unique:users,id_number',
            'gender' => 'nullable|in:male,female,other',
            'nationality' => 'nullable|string|max:100',
        ];
    }

    protected function getStep3Rules()
    {
        $rules = [
            'accept_terms' => 'required|accepted',
        ];

        if ($this->role === 'student') {
            $rules['student_number'] = 'required|string|unique:users,student_number';
            $rules['academic_year'] = 'nullable|integer|min:' . (date('Y') - 5) . '|max:' . (date('Y') + 5);
            $rules['course_area'] = 'nullable|string|max:255';
        }
        elseif ($this->role === 'teacher') {
            $rules['qualification'] = 'required|string|max:255';
            $rules['specializations'] = 'required|array|min:1';
            $rules['experience_years'] = 'nullable|integer|min:0|max:50';
        }
        elseif ($this->role === 'parent') {
            $rules['student_email'] = 'required|email|exists:users,email';
            $rules['relationship'] = 'required|in:father,mother,guardian,other';
            $rules['parent_notes'] = 'nullable|string|max:500';
        }

        return $rules;
    }

    protected $messages = [
        // Step 1
        'name.required' => 'O nome completo é obrigatório.',
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'Digite um email válido.',
        'email.unique' => 'Este email já está registado.',
        'phone.required' => 'O telefone é obrigatório.',
        'phone.min' => 'O telefone deve ter pelo menos 9 dígitos.',
        'phone.unique' => 'Este telefone já está registado.',
        'password.required' => 'A senha é obrigatória.',
        'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
        'password_confirmation.required' => 'A confirmação de senha é obrigatória.',
        'password_confirmation.same' => 'As senhas não coincidem.',
        'role.required' => 'Selecione o tipo de usuário.',

        // Step 2
        'birth_date.required' => 'A data de nascimento é obrigatória.',
        'birth_date.date' => 'Digite uma data válida.',
        'birth_date.before' => 'A data de nascimento deve ser anterior a hoje.',
        'address.required' => 'O endereço é obrigatório.',
        'id_number.required' => 'O documento de identificação é obrigatório.',
        'id_number.unique' => 'Este documento já está registado.',

        // Step 3
        'accept_terms.required' => 'Você deve aceitar os termos e condições.',
        'accept_terms.accepted' => 'Você deve aceitar os termos e condições.',
        'student_number.required' => 'O número de estudante é obrigatório.',
        'student_number.unique' => 'Este número de estudante já está registado.',
        'student_email.required' => 'O email do estudante é obrigatório.',
        'student_email.exists' => 'O email do estudante não existe no sistema.',
        'relationship.required' => 'O parentesco é obrigatório.',
        'qualification.required' => 'A qualificação acadêmica é obrigatória.',
        'specializations.required' => 'Selecione pelo menos uma especialização.',
    ];

    public function mount()
    {
        $this->academic_year = (string) date('Y');
        $this->nationality = 'Angolana';
        $this->experience_years = 0;
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function updated($property, $value)
    {
        // Limpar erro específico quando o campo é atualizado
        if (isset($this->errors[$property])) {
            unset($this->errors[$property]);
        }

        // Formatar telefone
        if ($property === 'phone') {
            $this->phone = $this->formatPhone($value);
        }

        // Formatar documento
        if ($property === 'id_number') {
            $this->id_number = $this->formatDocument($value);
        }

        // Validar confirmação de senha em tempo real
        if ($property === 'password' || $property === 'password_confirmation') {
            if (!empty($this->password) && !empty($this->password_confirmation)) {
                if ($this->password !== $this->password_confirmation) {
                    $this->errors['password_confirmation'] = 'As senhas não coincidem.';
                } else {
                    unset($this->errors['password_confirmation']);
                }
            }
        }
    }

    private function formatPhone($value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);

        if (strlen($value) > 0) {
            if (strlen($value) <= 3) {
                $value = '(' . $value;
            } elseif (strlen($value) <= 6) {
                $value = '(' . substr($value, 0, 3) . ') ' . substr($value, 3);
            } else {
                $value = '(' . substr($value, 0, 3) . ') ' . substr($value, 3, 3) . ' ' . substr($value, 6, 3);
            }
        }

        return $value;
    }

    private function formatDocument($value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);

        if (strlen($value) <= 11) {
            $value = preg_replace('/(\d{3})(\d)/', '$1.$2', $value);
            $value = preg_replace('/(\d{3})(\d)/', '$1.$2', $value);
            $value = preg_replace('/(\d{3})(\d{1,2})$/', '$1-$2', $value);
        }

        return $value;
    }

    public function validateStep1()
    {
        $this->errors = [];
        $rules = $this->getStep1Rules();
        $valid = true;

        // Validação individual de cada campo
        foreach ($rules as $field => $rule) {
            $data = [$field => $this->$field];

            // Para validação de confirmação, precisamos enviar ambos os campos
            if ($field === 'password_confirmation') {
                $data = [
                    'password' => $this->password,
                    'password_confirmation' => $this->password_confirmation
                ];
                $rule = 'required|same:password';
            }

            $validator = Validator::make($data, [$field => $rule], $this->messages);

            if ($validator->fails()) {
                $this->errors[$field] = $validator->errors()->first($field);
                $valid = false;
            }
        }

        // Validação adicional para garantir que ambos os campos de senha foram preenchidos
        if (empty($this->password) && empty($this->errors['password'])) {
            $this->errors['password'] = 'A senha é obrigatória.';
            $valid = false;
        }

        if (empty($this->password_confirmation) && empty($this->errors['password_confirmation'])) {
            $this->errors['password_confirmation'] = 'A confirmação de senha é obrigatória.';
            $valid = false;
        }

        return $valid;
    }

    public function validateStep2()
    {
        $this->errors = [];
        $rules = $this->getStep2Rules();
        $valid = true;

        foreach ($rules as $field => $rule) {
            $validator = Validator::make([$field => $this->$field], [$field => $rule], $this->messages);

            if ($validator->fails()) {
                $this->errors[$field] = $validator->errors()->first($field);
                $valid = false;
            }
        }

        return $valid;
    }

    public function validateStep3()
    {
        $this->errors = [];
        $rules = $this->getStep3Rules();
        $valid = true;

        foreach ($rules as $field => $rule) {
            $validator = Validator::make([$field => $this->$field], [$field => $rule], $this->messages);

            if ($validator->fails()) {
                $this->errors[$field] = $validator->errors()->first($field);
                $valid = false;
            }
        }

        // Validação adicional para email do estudante
        if ($this->role === 'parent' && !empty($this->student_email) && empty($this->errors['student_email'])) {
            $studentExists = User::where('email', $this->student_email)
                ->where('role', 'student')
                ->exists();

            if (!$studentExists) {
                $this->errors['student_email'] = 'Estudante não encontrado. Verifique o email.';
                $valid = false;
            }
        }

        return $valid;
    }

    public function nextStep()
    {
        if ($this->step == 1) {
            if ($this->validateStep1()) {
                $this->step = 2;
                $this->errors = [];
            }
        } elseif ($this->step == 2) {
            if ($this->validateStep2()) {
                $this->step = 3;
                $this->errors = [];
            }
        }
    }

    public function previousStep()
    {
        $this->step--;
        $this->errors = [];
    }

    public function register()
    {
        if (!$this->validateStep3()) {
            return;
        }

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => preg_replace('/[^0-9]/', '', $this->phone),
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'birth_date' => $this->birth_date,
                'address' => $this->address,
                'id_number' => $this->id_number,
                'is_active' => false,
                'is_approved' => false,
            ];

            // Campos opcionais
            if (!empty($this->gender)) {
                $userData['gender'] = $this->gender;
            }

            if (!empty($this->nationality)) {
                $userData['nationality'] = $this->nationality;
            }

            // Campos específicos por role
            if ($this->role === 'student') {
                $userData['student_number'] = $this->student_number;
                $userData['academic_year'] = !empty($this->academic_year) ? (int) $this->academic_year : null;
                $userData['course_area'] = $this->course_area;
            }
            elseif ($this->role === 'teacher') {
                $userData['qualification'] = $this->qualification;
                $userData['specializations'] = json_encode($this->specializations);
                $userData['experience_years'] = (int) $this->experience_years;
            }

            $user = User::create($userData);

            // Vincular responsável ao aluno
            if ($this->role === 'parent' && !empty($this->student_email)) {
                $student = User::where('email', $this->student_email)
                    ->where('role', 'student')
                    ->first();

                if ($student) {
                    $user->children()->attach($student->id, [
                        'relationship' => $this->relationship,
                        'notes' => $this->parent_notes,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            event(new UserRegistered($user));

            session()->flash('message', '✅ Sua inscrição foi enviada com sucesso! Aguarde a aprovação da secretaria.');

            $this->resetForm();

            return redirect()->route('login');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro no registro: ' . $e->getMessage(), [
                'email' => $this->email,
                'role' => $this->role,
                'trace' => $e->getTraceAsString()
            ]);

            $this->errors['register'] = 'Ocorreu um erro ao processar seu registro. Por favor, tente novamente.';
        }
    }

    private function resetForm()
    {
        $this->step = 1;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
        $this->birth_date = '';
        $this->address = '';
        $this->id_number = '';
        $this->gender = '';
        $this->nationality = 'Angolana';
        $this->accept_terms = false;
        $this->student_number = '';
        $this->academic_year = (string) date('Y');
        $this->course_area = '';
        $this->student_email = '';
        $this->relationship = '';
        $this->parent_notes = '';
        $this->qualification = '';
        $this->specializations = [];
        $this->experience_years = 0;
        $this->errors = [];
    }

    public function getError($field)
    {
        return $this->errors[$field] ?? null;
    }

    public function hasError($field)
    {
        return isset($this->errors[$field]);
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.guest');
    }
}