<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::with(['teacher.user'])
            ->orderBy('name', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subjects = $query->paginate(20);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $teachers = Teacher::with('user')->where('status', 'active')->get();
        return view('admin.subjects.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:subjects',
            'code' => 'required|string|max:20|unique:subjects',
            'description' => 'nullable|string|max:500',
            'workload' => 'required|integer|min:1|max:200',
            'teacher_id' => 'nullable|exists:teachers,id',
            'status' => 'required|in:active,inactive',
            'grade_level' => 'required|string|max:50',
        ]);

        $subject = Subject::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'workload' => $request->workload,
            'teacher_id' => $request->teacher_id,
            'status' => $request->status,
            'grade_level' => $request->grade_level,
        ]);

        return redirect()->route('admin.subjects.show', $subject)
            ->with('success', 'Disciplina criada com sucesso!');
    }

    public function show(Subject $subject)
    {
        $subject->load(['teacher.user', 'turmas.students']);
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $teachers = Teacher::with('user')->where('status', 'active')->get();
        return view('admin.subjects.edit', compact('subject', 'teachers'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('subjects')->ignore($subject->id)
            ],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('subjects')->ignore($subject->id)
            ],
            'description' => 'nullable|string|max:500',
            'workload' => 'required|integer|min:1|max:200',
            'teacher_id' => 'nullable|exists:teachers,id',
            'status' => 'required|in:active,inactive',
            'grade_level' => 'required|string|max:50',
        ]);

        $subject->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'workload' => $request->workload,
            'teacher_id' => $request->teacher_id,
            'status' => $request->status,
            'grade_level' => $request->grade_level,
        ]);

        return redirect()->route('admin.subjects.show', $subject)
            ->with('success', 'Disciplina atualizada com sucesso!');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')
            ->with('success', 'Disciplina removida com sucesso!');
    }
}
