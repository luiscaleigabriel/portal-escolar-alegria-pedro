<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Absence::class);

        $absences = Absence::with(['student', 'subject'])->get();
        return response()->json($absences);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Absence::class);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'justified' => 'required|boolean'
        ]);

        $absence = Absence::create($validated);

        return response()->json($absence, 201);
    }

    public function show(Absence $absence)
    {
        $this->authorize('view', $absence);
        return response()->json($absence->load(['student', 'subject']));
    }

    public function update(Request $request, Absence $absence)
    {
        $this->authorize('update', $absence);

        $validated = $request->validate([
            'date' => 'sometimes|date',
            'justified' => 'sometimes|boolean'
        ]);

        $absence->update($validated);

        return response()->json($absence);
    }

    public function destroy(Absence $absence)
    {
        $this->authorize('delete', $absence);
        $absence->delete();

        return response()->json(['message' => 'Absence deleted successfully']);
    }
}
