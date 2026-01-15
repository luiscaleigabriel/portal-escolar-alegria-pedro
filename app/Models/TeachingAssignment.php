<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachingAssignment extends Model
{
    protected $fillable = ['teacher_id','subject_id','turma_id'];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function turma() {
        return $this->belongsTo(Turma::class);
    }
}
