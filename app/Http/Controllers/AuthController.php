<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        $loged = Auth::attempt($credentials);

        if ($loged) {
            return redirect()->route('task.index');
        }

        return back()->with('error', 'Credencias incorretas!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalida a sessão e o token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
