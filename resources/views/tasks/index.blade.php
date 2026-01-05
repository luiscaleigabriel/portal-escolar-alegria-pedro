@extends('master')
@section('title', 'Minhas Tarefas')

@section('content')

    <div class="container mt-4">
        <h2 class="mb-4">Minhas Tarefas</h2>
        @if (session()->has('success'))
            <div class="alert danger-alert">
                {{ session()->get('success') }}
            </div>
        @endif
        <a href="{{ route('task.create') }}" class="btn btn-primary mb-2">Criar Tarefa</a>
        <table class="table table-dark table-striped">
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Status</th>
                <th>Prioridade</th>
                <th>Ação</th>
            </tr>

            @forelse ($tasks as $task)
                <tr>
                    <td>{{$task->name}}</td>
                    <td>{{$task->description}}</td>
                    <td>{{$task->status_label}}</td>
                    <td>{{$task->priority_label}}</td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('task.edit', $task->id) }}" class="btn btn-success">Editar</a>
                        <form action="{{ route('task.destroy', $task->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger" type="submit">Deletar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <td colspan="5" style="color: red">Nenhuma tarefa encontrada!</td>
            @endforelse
        </table>
    </div>
@endsection
