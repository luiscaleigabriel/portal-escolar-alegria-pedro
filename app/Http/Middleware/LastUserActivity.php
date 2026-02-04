<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LastUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Verificar se é um novo login (sem last_login_at)
            $isNewLogin = !$user->last_login_at || $user->last_login_at->diffInMinutes(now()) > 5;

            $updates = [
                'last_seen' => now(),
            ];

            if ($isNewLogin) {
                $updates['last_login_at'] = now();
                $updates['login_count'] = $user->login_count + 1;
            }

            $user->update($updates);

            // Marcar como online no cache por 5 minutos
            $expiresAt = now()->addMinutes(5);
            Cache::put('user-is-online-' . $user->id, true, $expiresAt);
        }

        return $next($request);
    }
}
