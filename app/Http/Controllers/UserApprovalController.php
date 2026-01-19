<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserApprovedMail;
use App\Mail\UserRejectedMail;

class UserApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|director']);
    }

    // Listar usuários pendentes
    public function pending()
    {
        $pendingUsers = User::with('roles')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.users.pending', compact('pendingUsers'));
    }

    // Ver detalhes do usuário
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $profile = null;

        // Carregar perfil específico
        if ($user->hasRole('student')) {
            $profile = $user->student;
        } elseif ($user->hasRole('teacher')) {
            $profile = $user->teacher;
        } elseif ($user->hasRole('guardian')) {
            $profile = $user->guardian;
        }

        return view('admin.users.show', compact('user', 'profile'));
    }

    // Aprovar usuário
    public function approve(Request $request, User $user)
    {
        $this->authorize('approve', $user);

        $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $user->approve(auth()->id());

        // Enviar email de aprovação
        Mail::to($user->email)->send(new UserApprovedMail($user, $request->notes));

        // Criar perfil se necessário (para professores/guardians que não criaram no registro)
        if ($user->hasRole('teacher') && !$user->teacher) {
            \App\Models\Teacher::create(['user_id' => $user->id]);
        }

        if ($user->hasRole('guardian') && !$user->guardian) {
            \App\Models\Guardian::create(['user_id' => $user->id]);
        }

        return redirect()->route('admin.users.pending')
                         ->with('success', "Usuário {$user->name} aprovado com sucesso!");
    }

    // Rejeitar usuário
    public function reject(Request $request, User $user)
    {
        $this->authorize('reject', $user);

        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user->reject($request->reason, auth()->id());

        // Enviar email de rejeição
        Mail::to($user->email)->send(new UserRejectedMail($user, $request->reason));

        return redirect()->route('admin.users.pending')
                         ->with('success', "Usuário {$user->name} rejeitado.");
    }

    // Suspender usuário
    public function suspend(Request $request, User $user)
    {
        $this->authorize('suspend', $user);

        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user->suspend($request->reason, auth()->id());

        return redirect()->back()
                         ->with('success', "Usuário {$user->name} suspenso.");
    }

    // Listar todos os usuários
    public function index()
    {
        $users = User::with('roles', 'approver')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    // Estatísticas
    public function stats()
    {
        $stats = [
            'total' => User::count(),
            'pending' => User::where('status', 'pending')->count(),
            'approved' => User::where('status', 'approved')->count(),
            'rejected' => User::where('status', 'rejected')->count(),
            'suspended' => User::where('status', 'suspended')->count(),

            'by_role' => [
                'student' => User::whereHas('roles', fn($q) => $q->where('name', 'student'))->count(),
                'teacher' => User::whereHas('roles', fn($q) => $q->where('name', 'teacher'))->count(),
                'guardian' => User::whereHas('roles', fn($q) => $q->where('name', 'guardian'))->count(),
                'admin' => User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->count(),
                'director' => User::whereHas('roles', fn($q) => $q->where('name', 'director'))->count(),
            ]
        ];

        return view('admin.users.stats', compact('stats'));
    }
}
