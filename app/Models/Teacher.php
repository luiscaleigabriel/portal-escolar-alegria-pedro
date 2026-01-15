<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory;

    protected $fillable = ['user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function turmas() {
        return $this->belongsToMany(Turma::class,'teacher_subject_turma');
    }

    public function subjects() {
        return $this->belongsToMany(Subject::class,'teacher_subject_turma');
    }
}
