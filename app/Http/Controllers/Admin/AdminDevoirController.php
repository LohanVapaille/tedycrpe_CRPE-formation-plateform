<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RenduDevoir;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminDevoirController extends Controller
{
    public function index()
    {
        $students = User::whereHas('rendus')
            ->with(['rendus.section.cours.matiere'])
            ->get();

        return view('admin.devoirs.index', compact('students'));
    }

    public function download(RenduDevoir $rendu)
    {
        $cheminRaw = $rendu->chemin_fichier;

        // 1. On retire un éventuel slash "/" au tout début de la chaîne pour uniformiser
        $cheminRaw = ltrim($cheminRaw, '/');

        // 2. On nettoie le préfixe web "public/storage/" s'il est présent
        if (Str::startsWith($cheminRaw, 'public/storage/')) {
            $cheminNettoye = Str::after($cheminRaw, 'public/storage/');
        } else {
            $cheminNettoye = $cheminRaw;
        }

        // 3. On vérifie et on télécharge sur le disque local 'public'
        if (!Storage::disk('public')->exists($cheminNettoye)) {
            return back()->with('error', 'Le fichier n\'existe plus sur le serveur. (Chemin testé : public/' . $cheminNettoye . ')');
        }

        return Storage::disk('public')->download($cheminNettoye, $rendu->nom_origine);
    }
}