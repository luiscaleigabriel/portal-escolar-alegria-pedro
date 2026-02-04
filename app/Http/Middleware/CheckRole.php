<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Admin tem acesso a tudo
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Verificar se o usuário tem um dos roles permitidos
        if (!in_array($user->role, $roles)) {
            // Se não tiver permissão, redirecionar para o dashboard apropriado
            $route = match ($user->role) {
                'student' => 'student.dashboard',
                'teacher' => 'teacher.dashboard',
                'parent' => 'parent.dashboard',
                'secretary' => 'secretary.dashboard',
                default => 'dashboard'
            };

            return redirect()->route($route)
                ->with('error', 'Acesso não autorizado para esta área.');
        }

        return $next($request);
    }
}
