<?php

namespace App\Http\Controllers;

use App\Models\Message;
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
}