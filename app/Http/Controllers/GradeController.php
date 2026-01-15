<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        // Apenas exibe notas que o usuário pode ver
        $this->authorize('viewAny', Grade::class);

        $grades = Grade::with(['student', 'subject'])->get();
        return response()->json($grades);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Grade::class);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'value' => 'required|numeric|min:0|max:20',
            'term' => 'required|string'
        ]);

        $grade = Grade::create($validated);

        return response()->json($grade, 201);
    }

    public function show(Grade $grade)
    {
        $this->authorize('view', $grade);
        return response()->json($grade->load(['student', 'subject']));
    }

    public function update(Request $request, Grade $grade)
    {
        $this->authorize('update', $grade);

        $validated = $request->validate([
            'value' => 'sometimes|numeric|min:0|max:20',
            'term' => 'sometimes|string'
        ]);

        $grade->update($validated);

        return response()->json($grade);
    }

    public function destroy(Grade $grade)
    {
        $this->authorize('delete', $grade);
        $grade->delete();

        return response()->json(['message' => 'Grade deleted successfully']);
    }
}
