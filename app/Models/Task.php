<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'priority',
        'user_id',
    ];

    public function getPriorityLabelAttribute()
    {
        return match ($this->priority) {
            'a' => 'Alta',
            'm' => 'Média',
            'b' => 'Baixa',
            default => 'Desconhecida'
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'd' => 'Concluida',
            'p' => 'Pendente',
            default => 'Pendente'
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
