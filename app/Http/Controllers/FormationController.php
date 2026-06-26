<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormationController extends Controller
{
    private function getAuthorizedMatieres($user)
    {
        $query = Matiere::with('cours')->orderBy('ordre', 'asc');

        // Correction ici : on utilise ->access au lieu de ->acces_formation
        if ($user->access !== 'all') {
            $query->where('module_associe', $user->access);
        }

        return $query->get();
    }

    public function index()
    {
        $user = Auth::user();
        $matieres = $this->getAuthorizedMatieres($user);

        return view('formation.index', compact('matieres'));
    }

    public function showRoute($id)
    {
        $user = Auth::user();

        $matieres = $this->getAuthorizedMatieres($user);

        $coursSelectionne = Cours::with([
            'sections' => function ($query) {
                $query->orderBy('ordre', 'asc');
            }
        ])->findOrFail($id);

        // Correction ici aussi pour la sécurité du cours
        if ($user->access !== 'all' && $coursSelectionne->matiere->module_associe !== $user->access) {
            abort(403, 'Vous n\'avez pas accès à ce contenu.');
        }

        return view('formation.show', compact('matieres', 'coursSelectionne'));
    }
}