<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatThread;
use App\Models\Message;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Guardian;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|director']);
    }

    public function index(Request $request)
    {
        // Carregar todas as threads com participantes e mensagens
        $threads = ChatThread::with(['participants.user', 'messages.sender'])
            ->latest()
            ->paginate(20);

        // Thread ativa (se especificada)
        $activeThread = null;
        if ($request->has('thread')) {
            $activeThread = ChatThread::with(['participants.user', 'messages.sender'])
                ->find($request->thread);
        }

        // Carregar usuários para novo chat
        $teachers = Teacher::with('user')->get();
        $students = Student::with(['user', 'turma'])->get();
        $guardians = Guardian::with(['user', 'students'])->get();

        // Estatísticas
        $totalMessages = Message::count();
        $totalParticipants = \DB::table('chat_participants')->distinct('user_id')->count('user_id');
        $activeToday = ChatThread::whereDate('updated_at', today())->count();

        return view('admin.chat.index', compact(
            'threads',
            'activeThread',
            'teachers',
            'students',
            'guardians',
            'totalMessages',
            'totalParticipants',
            'activeToday'
        ));
    }

    public function export(ChatThread $thread)
    {
        $this->authorize('view', $thread);

        $messages = $thread->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        $content = "Conversa: {$thread->subject}\n";
        $content .= "Data: " . now()->format('d/m/Y H:i') . "\n";
        $content .= "Participantes: " . $thread->participants->map(fn($p) => $p->user->name)->implode(', ') . "\n";
        $content .= str_repeat('=', 50) . "\n\n";

        foreach ($messages as $message) {
            $content .= "[{$message->created_at->format('d/m/Y H:i')}] {$message->sender->name}:\n";
            $content .= "{$message->content}\n";
            $content .= str_repeat('-', 30) . "\n";
        }

        $filename = "chat-{$thread->id}-" . now()->format('Y-m-d') . ".txt";

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'participants' => 'required|array|min:2'
        ]);

        $thread = ChatThread::create([
            'subject' => $request->subject,
        ]);

        // Adicionar participantes
        foreach ($request->participants as $userId) {
            $thread->participants()->create([
                'user_id' => $userId
            ]);
        }

        // Adicionar administrador como participante
        $thread->participants()->create([
            'user_id' => auth()->id()
        ]);

        // Mensagem de boas-vindas
        Message::create([
            'chat_thread_id' => $thread->id,
            'sender_user_id' => auth()->id(),
            'content' => 'Conversa iniciada pela administração.'
        ]);

        return redirect()->route('admin.chat.index', ['thread' => $thread->id])
                         ->with('success', 'Conversa criada com sucesso!');
    }

    public function destroy(ChatThread $thread)
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        return redirect()->route('admin.chat.index')
                         ->with('success', 'Conversa arquivada com sucesso!');
    }
}
