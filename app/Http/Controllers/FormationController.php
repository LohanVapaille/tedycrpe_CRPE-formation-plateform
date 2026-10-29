<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\Cours;
use App\Models\RenduDevoir;
use App\Models\SectionCours;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FormationController extends Controller
{
    private function getAuthorizedMatieres($user)
    {
        return Matiere::with('cours')->accessiblesPar($user)->orderBy('ordre', 'asc')->get();
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
                $matiere->progression = (int) round(($coursTerminesMatiere / $totalCoursMatiere) * 100);
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

        // 1re connexion : aucun cours ouvert ni validé -> message d'accueil au lieu de "Reprenez là où..."
        $premiereVisite = !$user->dernier_cours_id && $totalCoursTermines === 0;

        // Reprise au dernier cours ouvert, s'il fait toujours partie de sa formation
        $coursAccessiblesIds = $matieres->pluck('cours')->flatten()->pluck('id');
        if ($user->dernier_cours_id && $coursAccessiblesIds->contains($user->dernier_cours_id)) {
            $premierCoursId = $user->dernier_cours_id;
        }
        // -----------------------------------------------------

        // 4. Nombre de fichiers envoyés (Devoirs)
        $nombreFichiersEnvoyes = RenduDevoir::where('user_id', $user->id)->count();

        // Le compteur n'a de sens que si au moins un cours de sa formation contient une zone de dépôt
        $coursIds = $matieres->pluck('cours')->flatten()->pluck('id');
        $afficherDevoirs = $nombreFichiersEnvoyes > 0
            || SectionCours::where('type', 'zonedepot')->whereIn('id_cours', $coursIds)->exists();

        // 5. Progression globale (pour la bannière)
        $progressionGlobale = $totalCoursFormation > 0
            ? (int) round(($totalCoursTermines / $totalCoursFormation) * 100)
            : 0;

        return view('formation.index', compact(
            'chat',
            'matieres',
            'progressionGlobale',
            'totalCoursTermines',
            'totalCoursFormation',
            'nombreFichiersEnvoyes',
            'afficherDevoirs',
            'premiereVisite',
            'premierCoursId' // On passe la variable à ta vue index
        ));
    }

    // Méthode appelée quand on clique sur "Cours suivant"
    public function terminerCours($id)
    {
        $user = Auth::user();

        // Valider le cours actuel en BDD sans faire de doublon
        $user->coursTermines()->syncWithoutDetaching([$id]);

        $coursActuel = Cours::with('matiere')->findOrFail($id);

        // 1. Cours suivant dans la même matière (liste déjà triée par ordre, puis id)
        $coursMatiere = $coursActuel->matiere->cours->values();
        $position = $coursMatiere->search(fn($c) => $c->id === $coursActuel->id);
        $coursSuivant = $position !== false ? $coursMatiere->get($position + 1) : null;

        if ($coursSuivant) {
            return redirect()->route('formation.cours', $coursSuivant->id)
                ->with('success', 'Cours validé ! Passage au chapitre suivant.');
        }

        // 2. Dernier cours de la matière : premier cours de la matière suivante du même niveau (Bac +5 / Bac +3)
        // (dans l'ordre d'affichage : blocs Leçon, EPS, Entretien, puis ordre du module)
        $matieresDuNiveau = Matiere::parBloc(
            $this->getAuthorizedMatieres($user)
                ->where('module_associe', $coursActuel->matiere->module_associe)
                ->values()
        )->flatMap(fn($groupe) => $groupe['matieres'])->values();
        $positionMatiere = $matieresDuNiveau->search(fn($m) => $m->id === $coursActuel->id_matiere);

        $matiereSuivante = $positionMatiere === false ? null : $matieresDuNiveau
            ->slice($positionMatiere + 1)
            ->first(fn($m) => $m->cours->isNotEmpty());

        if ($matiereSuivante) {
            return redirect()->route('formation.cours', $matiereSuivante->cours->first()->id)
                ->with('success', 'Félicitations, vous avez terminé ce module ! Passage au module suivant : ' . $matiereSuivante->name . '.');
        }

        // 3. Plus aucun module après : fin du parcours
        return redirect()->route('formation.cours', $coursActuel->id)
            ->with('success', 'Félicitations, vous avez terminé ce module !');
    }

    public function showRoute($id)
    {
        $user = Auth::user();

        // 1. Récupérer toutes les matières autorisées
        $matieres = $this->getAuthorizedMatieres($user);

        // 2. Récupérer les cours terminés pour calculer les pourcentages
        $coursTerminesIds = $user->coursTermines()->pluck('id_cours')->toArray();

        // 3. Calculer la progression pour chaque matière (pour alimenter la sidebar)
        foreach ($matieres as $m) {
            $totalCoursMatiere = $m->cours->count();
            if ($totalCoursMatiere > 0) {
                $coursTerminesMatiere = $m->cours->whereIn('id', $coursTerminesIds)->count();
                $m->progression = (int) round(($coursTerminesMatiere / $totalCoursMatiere) * 100);
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
        if (!$user->aAccesA($coursSelectionne->matiere)) {
            abort(403, 'Vous n\'avez pas accès à ce contenu.');
        }

        // Mémorise le dernier cours ouvert (bouton "Accéder à ma formation" du tableau de bord)
        if ($user->dernier_cours_id !== $coursSelectionne->id) {
            $user->forceFill(['dernier_cours_id' => $coursSelectionne->id])->save();
        }

        return view('formation.show', compact('matieres', 'coursSelectionne'));
    }

    // Téléchargement d'une ressource ("fichier") sous le titre de la section plutôt que le nom aléatoire du stockage
    public function telechargerRessource(SectionCours $section)
    {
        $user = Auth::user();
        $section->loadMissing('cours.matiere');

        abort_unless($section->type === 'fichier' && $section->contenu, 404);

        // Le fichier peut être dans storage/app/public (Laravel standard) ou directement dans
        // public/storage (cas de la prod OVH, sans lien symbolique) : on prend le premier qui existe
        $chemin = collect([
            Storage::disk('public')->path($section->contenu),
            public_path('storage/' . $section->contenu),
        ])->first(fn($c) => is_file($c));

        abort_unless($chemin, 404);

        // Même règle d'accès que pour afficher le cours
        if (!$user->aAccesA($section->cours->matiere)) {
            abort(403, 'Vous n\'avez pas accès à ce contenu.');
        }

        // Titre de la section sans les caractères interdits dans un nom de fichier, + extension d'origine
        $titre = trim(preg_replace('/[\\\\\/:*?"<>|]+/', '-', $section->titre ?: 'Ressource'));
        $extension = pathinfo($section->contenu, PATHINFO_EXTENSION);
        $nomFichier = $extension ? "{$titre}.{$extension}" : $titre;

        return response()->download($chemin, $nomFichier);
    }
}