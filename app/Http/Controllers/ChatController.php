<?php

namespace App\Http\Controllers;

use App\Models\ChatThread;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Abrir ou criar thread
    public function open($student_id,$subject_id)
    {
        $teacher = auth()->user()->teacher;

        $thread = ChatThread::firstOrCreate([
            'student_id' => $student_id,
            'teacher_id' => $teacher->id,
            'subject_id' => $subject_id
        ]);

        return $thread;
    }

    // Enviar mensagem
    public function send(Request $request, $thread_id)
    {
        $thread = ChatThread::findOrFail($thread_id);

        // validação de acesso
        if(!$this->canAccess($thread)) abort(403);

        return Message::create([
            'thread_id'=>$thread->id,
            'sender_user_id'=>auth()->id(),
            'message'=>$request->message
        ]);
    }

    private function canAccess(ChatThread $thread)
    {
        $user = auth()->user();

        if($user->hasRole('teacher') && $thread->teacher->user_id == $user->id) return true;
        if($user->hasRole('student') && $thread->student->user_id == $user->id) return true;

        if($user->hasRole('guardian')) {
            return $thread->student->guardians->contains('user_id',$user->id);
        }

        return false;
    }
}
