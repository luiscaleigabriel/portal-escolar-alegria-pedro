<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatThread extends Model
{
    /** @use HasFactory<\Database\Factories\ChatThreadFactory> */
    use HasFactory;

    protected $fillable = ['student_id','teacher_id','subject_id'];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function participants() {
        return $this->hasMany(ChatParticipant::class);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }
    
}
