<?php

namespace App\Http\Controllers;

use App\Models\ChatParticipant;
use App\Models\ChatThread;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        // Listar threads do usuário logado
        $threads = ChatThread::whereHas('participants', function($q){
            $q->where('user_id', auth()->id());
        })->with('participants.user', 'messages.sender')->get();

        return response()->json($threads);
    }

    public function store(Request $request)
    {
        $this->authorize('create', ChatThread::class);

        $request->validate([
            'subject' => 'required|string',
            'participants' => 'required|array|min:2' // student + guardian
        ]);

        $thread = ChatThread::create(['subject' => $request->subject]);

        // Adiciona Teacher (criador)
        ChatParticipant::create([
            'chat_thread_id' => $thread->id,
            'user_id' => auth()->id()
        ]);

        // Adiciona os outros participantes
        foreach ($request->participants as $userId) {
            ChatParticipant::create([
                'chat_thread_id' => $thread->id,
                'user_id' => $userId
            ]);
        }

        return response()->json($thread->load('participants.user'), 201);
    }

    public function message(Request $request, ChatThread $thread)
    {
        $this->authorize('message', $thread);

        $request->validate([
            'content' => 'required|string'
        ]);

        $message = Message::create([
            'chat_thread_id' => $thread->id,
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        return response()->json($message->load('sender'));
    }
}
