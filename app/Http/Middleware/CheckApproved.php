<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->is_approved) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Sua conta ainda não foi aprovada pela secretaria.');
        }

        return $next($request);
    }
}
