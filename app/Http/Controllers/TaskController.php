<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $tasks = Task::where('user_id', Auth::user()->id);
        $user = User::find(Auth::user()->id);
        $tasks = $user->tasks()
            ->orderByRaw("FIELD(status, 'pendente', 'concluido')")
            ->orderByRaw("FIELD(priority, 'a', 'm', 'b')")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'user_id' => Auth::user()->id,
        ];
        $created = Task::create($data);

        if ($created) {
            return redirect()->route('task.index')->with('success', 'Tarefa cadastrada com sucesso!');
        }

        return back()->with('error', 'Ocorreu um erro ao tentar cadastrar a tarefa. Por favor tente novamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::find($id);

        if($task) {
            return view('tasks.edit', compact('task'));
        }

        return back()->with('error', 'Tarefa não encontrada!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTaskRequest $request, string $id)
    {
        $data = $request->except(['_token', '_método']);

        $task = Task::find($id);

        if($task) {
            $task->update($data);
            return redirect()->route('task.index')->with('success', 'Dados da tarefa editados com sucesso!');
        }

        return back()->with('error', 'Ocorreu um erro ao editar a tarefa. Por favor tente mais tarde!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        $task->delete();

        return redirect()->route('task.index')->with('success', 'Tarefa excluída com sucesso!');
    }
}
