@extends('auth.layout')
@section('title', 'Cadastro')


@section('content')

    <div class="container mt-4">

        <h2>Sejá bem vindo!</h2>
        <p>Cadastre - se e gerencie suas tarefas agora.</p>

        <br>

        @if (session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('error') }}
            </div>
        @endif

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('user.store') }}">
            @csrf
            <div class="mb-3">
                <label for="exampleInputName" class="form-label">Nome</label>
                <input type="text" class="form-control" name="name" id="exampleInputName" value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="exampleInputEmail1"
                    aria-describedby="emailHelp" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1">
            </div>
            <div class="mb-3">
                Já possui uma conta? <a href="{{ route('login') }}">Fazer login</a>
            </div>
            <button type="submit" class="btn btn-primary">Criar</button>
        </form>
    </div>

@endsection
