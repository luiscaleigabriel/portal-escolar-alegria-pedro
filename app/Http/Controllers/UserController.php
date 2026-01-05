<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->except('_token');
        $created = User::create($data);

        if($created) {
            $credentials = $request->only(['email', 'password']);
            Auth::attempt($credentials);

            return redirect()->route('task.index');
        }

        return back()->with('error', 'Não foi possivel efetuar o cadastro!. Por favor tente mais tarde.');
    }
}
