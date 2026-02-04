<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rules\Password;

class Users extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $role = '';
    public $status = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $showForm = false;
    public $editMode = false;
    public $userId = null;

    // Form fields
    public $name = '';
    public $email = '';
    public $phone = '';
    public $roleForm = 'student';
    public $password = '';
    public $password_confirmation = '';
    public $is_active = true;
    public $is_approved = true;
    public $photo;

    public $showDeleteModal = false;
    public $userToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => ''],
        'status' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'phone' => 'required|string|max:20|unique:users,phone,' . $this->userId,
            'roleForm' => 'required|in:admin,secretary,teacher,student,parent',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
            'photo' => 'nullable|image|max:2048',
        ];

        if (!$this->editMode || $this->password) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        return $rules;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function createUser()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->roleForm,
            'is_active' => $this->is_active,
            'is_approved' => $this->is_approved,
            'password' => Hash::make($this->password),
        ];

        if ($this->editMode && $this->userId) {
            $user = User::find($this->userId);

            // Não atualizar a senha se não foi fornecida
            if (!$this->password) {
                unset($userData['password']);
            }

            $user->update($userData);
            $message = 'Usuário atualizado com sucesso!';
        } else {
            $user = User::create($userData);
            $message = 'Usuário criado com sucesso!';
        }

        // Processar foto se fornecida
        if ($this->photo) {
            $path = $this->photo->store('profiles', 'public');
            $user->update(['photo' => $path]);
        }

        $this->resetForm();
        session()->flash('success', $message);
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->roleForm = $user->role;
        $this->is_active = $user->is_active;
        $this->is_approved = $user->is_approved;

        $this->editMode = true;
        $this->showForm = true;
        $this->reset('password', 'password_confirmation');
    }

    public function confirmDelete($id)
    {
        $this->userToDelete = User::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        if ($this->userToDelete) {
            // Não permitir excluir a si mesmo
            if ($this->userToDelete->id === auth()->id()) {
                session()->flash('error', 'Você não pode excluir sua própria conta!');
                $this->closeDeleteModal();
                return;
            }

            $this->userToDelete->delete();
            session()->flash('success', 'Usuário excluído com sucesso!');
            $this->closeDeleteModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'ativada' : 'desativada';
        session()->flash('success', "Conta {$status} com sucesso!");
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_approved' => true]);
        session()->flash('success', 'Usuário aprovado com sucesso!');
    }

    public function resetForm()
    {
        $this->reset([
            'showForm', 'editMode', 'userId',
            'name', 'email', 'phone', 'roleForm',
            'password', 'password_confirmation',
            'is_active', 'is_approved', 'photo'
        ]);
    }

    public function render()
    {
        $users = User::when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->role, function($query) {
                $query->where('role', $this->role);
            })
            ->when($this->status === 'active', function($query) {
                $query->where('is_active', true);
            })
            ->when($this->status === 'inactive', function($query) {
                $query->where('is_active', false);
            })
            ->when($this->status === 'pending', function($query) {
                $query->where('is_approved', false);
            })
            ->when($this->status === 'approved', function($query) {
                $query->where('is_approved', true);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'pending' => User::where('is_approved', false)->count(),
        ];

        return view('livewire.admin.users', [
            'users' => $users,
            'stats' => $stats,
            'roles' => [
                'admin' => 'Administrador',
                'secretary' => 'Secretaria',
                'teacher' => 'Professor',
                'student' => 'Aluno',
                'parent' => 'Responsável',
            ]
        ])->layout('layouts.app', [
            'pageTitle' => 'Gerenciar Usuários',
            'pageSubtitle' => 'Administre todos os usuários do sistema'
        ]);
    }
}
