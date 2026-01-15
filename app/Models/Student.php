<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = ['user_id','registration_number','turma_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function turma() {
        return $this->belongsTo(Turma::class);
    }

    public function guardians() {
        return $this->belongsToMany(Guardian::class);
    }

    public function grades() {
        return $this->hasMany(Grade::class);
    }

    public function absences() {
        return $this->hasMany(Absence::class);
    }
}
