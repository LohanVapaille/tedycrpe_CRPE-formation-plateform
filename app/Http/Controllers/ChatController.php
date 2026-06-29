<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chat;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required|string',
            'id_chat' => 'required|exists:chat,id',
        ]);

        // 1. Sauvegarde en BDD
        // Attention : vérifie bien que tes colonnes s'appellent id_chat et id_writer
        $message = Message::create([
            'id_chat' => $request->id_chat,
            'id_writer' => Auth::id(),
            'contenu' => $request->contenu,
        ]);

        // 2. Déclenchement de l'événement Reverb (.toOthers() évite de te renvoyer le message à toi-même)
        broadcast(new MessageSent($message))->toOthers();

        // 3. LA RÉPONSE JSON POUR LE FETCH JAVASCRIPT
        return response()->json([
            'status' => 'success',
            'message' => $message->load('writer')
        ]);
    }

    // ChatController.php
    public function history(Chat $chat)
    {
        // Vérifie que seul le tuteur ou l'élève concerné peut accéder
        $user = Auth::user();
        abort_if(!$user->is_tutor && $user->id !== $chat->id_user, 403);

        $messages = $chat->messages()->with('writer')->orderBy('created_at')->get();

        return response()->json($messages);
    }

    public function tutorChannels()
    {
        if (!Auth::user()->is_tutor) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // On charge la relation 'user' (l'élève) et les 'messages'
            $chats = Chat::with(['user', 'messages'])->get()->map(function ($chat) {
                // Récupère le tout dernier message
                $chat->latest_message = $chat->messages()->latest()->first();
                return $chat;
            });

            return response()->json($chats);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur de relations ou de modèle',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}