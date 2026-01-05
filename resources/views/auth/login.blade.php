@extends('auth.layout')
@section('title', 'Login')

@section('content')

    <div class="container mt-4">

        <h2>Bem-vindo de volta!</h2>

        <br>
        @if (session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('error') }}
            </div>
        @endif

        @if ($errors->any)
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Senha</label>
                <input type="password" class="form-control" name="password" id="exampleInputPassword1">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Lembrar-me</label>
            </div>
            <div class="mb-3">
                Ainda não tem uma conta? <a href="{{ route('user.index') }}">Criar</a>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

@endsection
