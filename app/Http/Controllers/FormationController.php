<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\Cours;
use App\Models\RenduDevoir;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormationController extends Controller
{
    private function getAuthorizedMatieres($user)
    {
        $query = Matiere::with('cours')->orderBy('ordre', 'asc');
        if ($user->access !== 'all') {
            $query->where('module_associe', $user->access);
        }
        return $query->get();
    }

    public function index()
    {
        $user = Auth::user();

        $matieres = $this->getAuthorizedMatieres($user);

        if ($user->is_tutor) {
            // Le tuteur voit le premier chat disponible
            $chat = Chat::first();
        } else {
            // L'étudiant a son propre chat
            $chat = Chat::firstOrCreate(['id_user' => $user->id]);
        }

        // 1. IDs des cours terminés
        $coursTerminesIds = $user->coursTermines()->pluck('id_cours')->toArray();
        $totalCoursTermines = count($coursTerminesIds);

        // 2. Calcul du nombre total de cours disponibles pour sa formation
        $totalCoursFormation = 0;
        foreach ($matieres as $matiere) {
            $totalCoursFormation += $matiere->cours->count();

            // Calcul de progression par matière pour tes jauges
            $totalCoursMatiere = $matiere->cours->count();
            if ($totalCoursMatiere > 0) {
                $coursTerminesMatiere = $matiere->cours->whereIn('id', $coursTerminesIds)->count();
                $matiere->progression = round(($coursTerminesMatiere / $totalCoursMatiere) * 100, 2);
            } else {
                $matiere->progression = 0;
            }
        }

        // --- AJOUT : Déterminer le premier cours dynamique ---
        // On cherche la première matière de la liste qui possède au moins un cours
        $premiereMatiereAvecCours = $matieres->first(function ($matiere) {
            return $matiere->cours->isNotEmpty();
        });

        // Si on trouve une matière avec des cours, on extrait son tout premier cours (trié par ordre)
        if ($premiereMatiereAvecCours) {
            $premierCours = $premiereMatiereAvecCours->cours->sortBy('ordre')->first();
            $premierCoursId = $premierCours->id;
        } else {
            $premierCoursId = 1; // Fallback de sécurité si aucun cours n'est créé dans sa formation
        }
        // -----------------------------------------------------

        // 4. Nombre de fichiers envoyés (Devoirs)
        $nombreFichiersEnvoyes = RenduDevoir::where('user_id', $user->id)->count();

        // 5. Progression globale (pour la bannière)
        $progressionGlobale = $totalCoursFormation > 0
            ? round(($totalCoursTermines / $totalCoursFormation) * 100, 2)
            : 0;

        return view('formation.index', compact(
            'chat',
            'matieres',
            'progressionGlobale',
            'totalCoursTermines',
            'totalCoursFormation',
            'nombreFichiersEnvoyes',
            'premierCoursId' // On passe la variable à ta vue index
        ));
    }

    // Méthode appelée quand on clique sur "Cours suivant"
    public function terminerCours($id)
    {
        $user = Auth::user();

        // Valider le cours actuel en BDD sans faire de doublon
        $user->coursTermines()->syncWithoutDetaching([$id]);

        $coursActuel = Cours::findOrFail($id);
        $coursSuivant = Cours::where('id_matiere', $coursActuel->id_matiere)
            ->where('ordre', '>', $coursActuel->ordre)
            ->orderBy('ordre', 'asc')
            ->first();

        if ($coursSuivant) {
            return redirect()->route('formation.cours', $coursSuivant->id)
                ->with('success', 'Cours validé ! Passage au chapitre suivant.');
        }

        return redirect()->route('formation.cours', $coursActuel->id)
            ->with('success', 'Félicitations, vous avez fini cette matière !');
    }

    public function showRoute($id)
    {
        $user = Auth::user();

        // 1. Récupérer toutes les matières autorisées
        $queryMatieres = Matiere::with('cours')->orderBy('ordre', 'asc');
        if ($user->access !== 'all') {
            $queryMatieres->where('module_associe', $user->access);
        }
        $matieres = $queryMatieres->get();

        // 2. Récupérer les cours terminés pour calculer les pourcentages
        $coursTerminesIds = $user->coursTermines()->pluck('id_cours')->toArray();

        // 3. Calculer la progression pour chaque matière (pour alimenter la sidebar)
        foreach ($matieres as $m) {
            $totalCoursMatiere = $m->cours->count();
            if ($totalCoursMatiere > 0) {
                $coursTerminesMatiere = $m->cours->whereIn('id', $coursTerminesIds)->count();
                $m->progression = round(($coursTerminesMatiere / $totalCoursMatiere) * 100, 2);
            } else {
                $m->progression = 0;
            }
        }

        // 4. Charger le cours actuel avec ses sections
        $coursSelectionne = Cours::with([
            'sections' => function ($query) {
                $query->orderBy('ordre', 'asc');
            }
        ])->findOrFail($id);

        // Sécurité d'accès
        if ($user->access !== 'all' && $coursSelectionne->matiere->module_associe !== $user->access) {
            abort(403, 'Vous n\'avez pas accès à ce contenu.');
        }

        return view('formation.show', compact('matieres', 'coursSelectionne'));
    }
}