<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message; // Remplace par ton modèle de messages

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required|string',
            'id_chat' => 'required|exists:chat,id'
        ]);

        // Sauvegarde dans la base de données
        Message::create([
            'contenu' => $request->contenu,
            'id_writer' => auth()->id(), // L'ID de l'étudiant connecté
            'id_chat' => $request->id_chat,
        ]);

        // Recharger la page actuelle pour afficher le nouveau message
        return back();
    }
}
