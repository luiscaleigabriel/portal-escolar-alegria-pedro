<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'is_approved',
        'phone',
        'address',
        'birth_date',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
    ];

    // Relacionamentos
    public function children()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'parent_id', 'student_id')
            ->withPivot('relationship')
            ->withTimestamps();
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id')
            ->withPivot('relationship')
            ->withTimestamps();
    }

    public function coursesAsStudent()
    {
        return $this->belongsToMany(Course::class, 'student_course', 'student_id', 'course_id')
            ->withPivot('status', 'academic_year')
            ->withTimestamps();
    }

    public function coursesAsTeacher()
    {
        return $this->belongsToMany(Course::class, 'course_subject', 'teacher_id', 'course_id')
            ->withTimestamps();
    }

    public function subjectsAsTeacher()
    {
        return $this->belongsToMany(Subject::class, 'course_subject', 'teacher_id', 'subject_id')
            ->withPivot('course_id')
            ->withTimestamps();
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    public function givenGrades()
    {
        return $this->hasMany(Grade::class, 'teacher_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'teacher_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Scopes
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    public function scopeParents($query)
    {
        return $query->where('role', 'parent');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Métodos auxiliares
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isParent()
    {
        return $this->role === 'parent';
    }

    public function isSecretary()
    {
        return $this->role === 'secretary';
    }

    public function getFullPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : asset('images/default-avatar.png');
    }

}
