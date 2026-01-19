<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'image',
        'password',
        'phone',
        'address',
        'birth_date',
        'is_approved',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'approved_at' => 'datetime',
            'is_approved' => 'boolean',
        ];
    }

    // Relacionamentos
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Escopos
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Métodos de verificação
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved' || $this->is_approved;
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    // Métodos de ação
    public function approve($approvedBy)
    {
        $this->update([
            'is_approved' => true,
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy,
        ]);
    }

    public function reject($reason, $rejectedBy)
    {
        $this->update([
            'is_approved' => false,
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'approved_by' => $rejectedBy,
        ]);
    }

    public function suspend($reason, $suspendedBy)
    {
        $this->update([
            'is_approved' => false,
            'status' => 'suspended',
            'rejection_reason' => $reason,
            'approved_by' => $suspendedBy,
        ]);
    }
}
