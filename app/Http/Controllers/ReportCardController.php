<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportCardController extends Controller
{
    public function generate(Student $student)
    {
        $this->authorize('view', $student);

        $grades = $student->grades()->with('subject')->get();
        $absences = $student->absences()->with('subject')->get();

        $pdf = Pdf::loadView('pdf.report_card', compact('student','grades','absences'));
        return $pdf->download("Boletim_{$student->registration_number}.pdf");
    }
}
