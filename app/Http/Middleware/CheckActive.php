<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckActive
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Sua conta está desativada. Entre em contato com a administração.');
        }

        return $next($request);
    }
}
