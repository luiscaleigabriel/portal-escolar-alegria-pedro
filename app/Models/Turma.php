<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    /** @use HasFactory<\Database\Factories\TurmaFactory> */
    use HasFactory;

    protected $fillable = ['name','year'];

    public function students() {
        return $this->hasMany(Student::class);
    }

    public function teachers() {
        return $this->belongsToMany(Teacher::class,'teacher_subject_turma');
    }
}
