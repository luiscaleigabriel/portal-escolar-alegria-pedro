<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    /** @use HasFactory<\Database\Factories\AbsenceFactory> */
    use HasFactory;

    protected $fillable = ['student_id','subject_id','date','justified'];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }
}
