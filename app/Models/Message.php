<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;

    protected $fillable = ['thread_id','sender_user_id','message'];

    public function thread() {
        return $this->belongsTo(ChatThread::class);
    }

    public function sender() {
        return $this->belongsTo(User::class,'sender_user_id');
    }
}
