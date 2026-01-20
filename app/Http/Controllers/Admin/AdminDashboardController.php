<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Turma;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
       // $this->middleware(['auth', 'role:admin|director']);
    }

    public function index()
    {
        $stats = $this->getStats();

        return view('admin.index', compact('stats'));
    }

    public function stats()
    {
        $stats = $this->getStats();

        return view('admin.users.stats', compact('stats'));
    }

    private function getStats()
    {
        return [
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

    // Ações em lote
    public function batchApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->ids)
                     ->where('status', 'pending')
                     ->get();

        foreach ($users as $user) {
            $user->approve(auth()->id());
        }

        return response()->json([
            'success' => true,
            'message' => $users->count() . ' usuários aprovados com sucesso!'
        ]);
    }

    public function batchReject(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
            'reason' => 'required|string|max:1000'
        ]);

        $users = User::whereIn('id', $request->ids)
                     ->where('status', 'pending')
                     ->get();

        foreach ($users as $user) {
            $user->reject($request->reason, auth()->id());
        }

        return response()->json([
            'success' => true,
            'message' => $users->count() . ' usuários rejeitados com sucesso!'
        ]);
    }
}
