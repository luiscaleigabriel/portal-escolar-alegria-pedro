<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    /** @use HasFactory<\Database\Factories\TurmaFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'grade_level',
        'school_year',
        'teacher_id',
        'capacity',
        'status',
        'description',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    // Scope para turmas ativas
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Outros scopes úteis
    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
                    ->whereColumn('students_count', '<', 'capacity');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'turma_subject')
                    ->withTimestamps();
    }

    // Acessor para contar estudantes
    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    // Acessor para verificar se tem vagas
    public function getHasVacanciesAttribute()
    {
        return $this->students_count < $this->capacity;
    }
}
