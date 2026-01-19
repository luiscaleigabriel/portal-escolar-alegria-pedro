<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApproval
{
    public function handle(Request $request, Closure $next): Response
    {
        // Se não estiver autenticado, continue
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Admin e Director não precisam de aprovação
        if ($user->hasRole(['admin', 'director'])) {
            return $next($request);
        }

        // Verificar status do usuário
        switch ($user->status) {
            case 'pending':
                if (!$request->routeIs('pending-approval', 'logout')) {
                    return redirect()->route('pending-approval');
                }
                break;

            case 'rejected':
                if (!$request->routeIs('account.rejected', 'logout')) {
                    return redirect()->route('account.rejected');
                }
                break;

            case 'suspended':
                if (!$request->routeIs('account.suspended', 'logout')) {
                    return redirect()->route('account.suspended');
                }
                break;

            case 'approved':
                // Usuário aprovado, pode continuar
                return $next($request);
        }

        return $next($request);
    }
}
