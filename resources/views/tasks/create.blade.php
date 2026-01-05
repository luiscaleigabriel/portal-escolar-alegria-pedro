@extends('master')
@section('title', 'Minhas Tarefas')

@section('content')

    <div class="container mt-5">
        @if (session()->has('error'))
            <div class="alert danger-alert">
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
        <div class="card shadow rounded">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Cadastrar Nova Tarefa</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('task.store') }}" method="POST">
                    @csrf

                    @include('tasks.partials.form')

                    <button type="submit" class="btn btn-success">Salvar Tarefa</button>
                    <a href="{{ route('task.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
@endsection
