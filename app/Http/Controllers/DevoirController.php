<?php

namespace App\Http\Controllers;

use App\Models\SectionCours;
use App\Models\RenduDevoir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;

class DevoirController extends Controller
{
    public function store(Request $request, SectionCours $section)
    {
        // 1. Validation simplifiée au maximum (juste "file" et "max 10Mo" temporairement pour tester)
        $request->validate([
            'devoir' => 'required|file|extensions:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ]);

        if ($request->hasFile('devoir')) {
            try {
                $file = $request->file('devoir');

                // On force le stockage
                $path = $file->store('exercices_rendus', 'public');

                // Si le fichier ne s'enregistre pas physiquement, on arrête tout
                if (!$path) {
                    return back()->with('error', 'Impossible d’écrire le fichier sur le disque.');
                }

                // 2. On effectue le updateOrCreate
                $rendu = RenduDevoir::updateOrCreate(
                    [
                        'section_cours_id' => $section->id,
                        'user_id' => Auth::id(),
                    ],
                    [
                        'nom_origine' => $file->getClientOriginalName(),
                        'chemin_fichier' => $path,
                    ]
                );

                // TEST : Est-ce que le modèle a bien été sauvegardé ?
                if ($rendu) {
                    return back()->with('success', 'BINGO ! Le devoir est enregistré. ID du rendu : ' . $rendu->id);
                }

                return back()->with('error', 'updateOrCreate a retourné une valeur vide.');

            } catch (Exception $e) {
                // S'il y a le moindre problème de base de données (colonne manquante, table introuvable), ça va s'afficher ici !
                return back()->with('error', 'Exception attrapée : ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Le fichier n\'a pas été détecté par hasFile().');
    }
}