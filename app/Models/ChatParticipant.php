<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
{
    /** @use HasFactory<\Database\Factories\ChatParticipantFactory> */
    use HasFactory;

    protected $fillable = ['thread_id','guardian_id'];

    public function thread() {
        return $this->belongsTo(ChatThread::class);
    }

    public function guardian() {
        return $this->belongsTo(Guardian::class);
    }
}
