<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class AdminMessageController extends Controller
{
    public function index()
    {
        // Récupère les messages du plus récent au plus ancien
        $messages = ContactMessage::latest()->paginate(10);
        return view('admin.messages.index', compact('messages'));
    }

    // Le paramètre correspond maintenant à {contact_message}
    public function toggleRead(ContactMessage $contactMessage)
    {
        $contactMessage->update(['is_read' => !$contactMessage->is_read]);
        return redirect()->back()->with('success', 'Statut du message mis à jour.');
    }

    // Le paramètre correspond maintenant à {contact_message}
    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return redirect()->back()->with('success', 'Message supprimé.');
    }
}