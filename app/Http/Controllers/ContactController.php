<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Mail\NewContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validation du Honeypot (Si rempli = robot)
        if ($request->filled('fax_number')) {
            return redirect()->back()->with('success', 'Votre message a été envoyé avec succès !');
        }

        // 2. Validation des vrais champs
        $validated = $request->validate([
            'email' => 'required|email|max:32',
            'message' => 'required|string|max:185',
        ]);

        // 3. Sauvegarde en BDD
        $messageCreated = ContactMessage::create($validated);

        // 4. Envoi du mail de notification
        Mail::to('lohanvapaille@gmail.fr')->send(new NewContactMessage($messageCreated));

        // Redirection vers la page précédente avec l'ancre #contact
        return redirect()->to(url()->previous() . '#contact')->with('success', 'Votre message a bien été transmis.');
    }
}