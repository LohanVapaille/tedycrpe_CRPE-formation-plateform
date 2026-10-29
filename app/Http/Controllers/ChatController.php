<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chat;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required|string|max:5000',
            'id_chat' => 'required|exists:chat,id',
        ]);

        $chat = Chat::findOrFail($request->id_chat);
        $this->authorizeChat($chat);

        // 1. Sauvegarde en BDD
        $message = Message::create([
            'id_chat' => $chat->id,
            'id_writer' => Auth::id(),
            'contenu' => $request->contenu,
        ]);

        // Répondre vaut lecture des messages reçus dans cette conversation
        $this->markAsRead($chat);

        // 2. Notification temps réel (.toOthers() évite de se renvoyer le message).
        // Si Pusher est indisponible, le message reste enregistré : on journalise sans bloquer l'envoi.
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Throwable $e) {
            Log::warning('Diffusion du message impossible', ['message_id' => $message->id, 'error' => $e->getMessage()]);
        }

        return response()->json([
            'status' => 'success',
            'message' => $message->load('writer:id,firstname,name'),
        ]);
    }

    public function history(Chat $chat)
    {
        $this->authorizeChat($chat);

        $messages = $chat->messages()
            ->with('writer:id,firstname,name')
            ->orderBy('created_at')
            ->get();

        // Ouvrir la conversation marque les messages reçus comme lus
        $this->markAsRead($chat);

        return response()->json($messages);
    }

    public function markRead(Chat $chat)
    {
        $this->authorizeChat($chat);
        $this->markAsRead($chat);

        return response()->json(['status' => 'success']);
    }

    /**
     * Nombre de messages non lus (total et par conversation) : sert au chargement
     * des badges et de filet de sécurité si le temps réel ne passe pas.
     */
    public function unread()
    {
        $counts = Message::unreadFor(Auth::user())
            ->selectRaw('id_chat, COUNT(*) as total')
            ->groupBy('id_chat')
            ->pluck('total', 'id_chat');

        return response()->json([
            'total' => (int) $counts->sum(),
            'chats' => $counts,
        ]);
    }

    public function tutorChannels()
    {
        abort_unless(Auth::user()->is_tutor, 403);

        // Conversations avec non lus en premier, puis par dernier message
        $chats = Chat::with(['user:id,firstname,name', 'latestMessage'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->whereNull('read_at')->whereColumn('messages.id_writer', 'chat.id_user');
            }])
            ->get()
            ->sort(function ($a, $b) {
                return [$b->unread_count > 0, $b->latestMessage?->created_at]
                    <=> [$a->unread_count > 0, $a->latestMessage?->created_at];
            })
            ->values();

        return response()->json($chats);
    }

    private function authorizeChat(Chat $chat): void
    {
        $user = Auth::user();
        abort_if(!$user->is_tutor && $user->id != $chat->id_user, 403);
    }

    private function markAsRead(Chat $chat): void
    {
        $chat->messages()
            ->whereNull('read_at')
            ->where('id_writer', '!=', Auth::id())
            ->update(['read_at' => now()]);
    }
}
