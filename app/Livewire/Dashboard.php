<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function mount()
    {
        // Redirecionar baseado no role
        $user = auth()->user();

        return match($user->role) {
            'student' => redirect()->route('student.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            'secretary' => redirect()->route('secretary.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => null,
        };
    }
    
    public function render()
    {
        return view('livewire.dashboard');
    }
}
