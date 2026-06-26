<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matiere;
use App\Models\Cours;
use App\Models\SectionCours;

class FormationSeeder extends Seeder
{
    public function run(): void
    {
        // On nettoie les tables pour éviter les doublons si on relance le seeder
        SectionCours::truncate();
        Cours::truncate();
        Matiere::truncate();

        // Configuration des formations et de leurs matières respectives
        $formations = [
            'CRPE BAC + 5' => [
                ['name' => 'Mathématiques B+5', 'ordre' => 1],
                ['name' => 'Français B+5', 'ordre' => 2],
                ['name' => 'Éducation Physique et Sportive B+5', 'ordre' => 3],
            ],
            'CRPE BAC + 3' => [
                ['name' => 'Mathématiques (Bases) B+3', 'ordre' => 1],
                ['name' => 'Français (Bases) B+3', 'ordre' => 2],
                ['name' => 'Histoire-Géographie B+3', 'ordre' => 3],
            ]
        ];

        foreach ($formations as $moduleAssocie => $matieres) {
            foreach ($matieres as $matData) {
                // 1. Création de la Matière
                $matiere = Matiere::create([
                    'name' => $matData['name'],
                    'module_associe' => $moduleAssocie,
                    'ordre' => $matData['ordre']
                ]);

                //génération de 3 cours par matière
                for ($i = 1; $i <= 3; $i++) {
                    $cours = Cours::create([
                        'name' => "Cours {$i} : Approfondissement sur le Chapitre {$i}",
                        'ordre' => $i,
                        'id_matiere' => $matiere->id
                    ]);

                    // 2. Création des Sections du cours (titre, txt, img, video, upload)
                    SectionCours::create([
                        'id_cours' => $cours->id,
                        'type' => 'titre',
                        'titre' => 'Introduction et Objectifs du cours',
                        'contenu' => null,
                        'ordre' => 1
                    ]);

                    SectionCours::create([
                        'id_cours' => $cours->id,
                        'type' => 'txt',
                        'titre' => null,
                        'contenu' => 'Bienvenue dans ce module de préparation intensive. Vous y trouverez les notions clés à maîtriser pour le concours du CRPE, accompagnées d\'explications didactiques claires.',
                        'ordre' => 2
                    ]);

                    SectionCours::create([
                        'id_cours' => $cours->id,
                        'type' => 'video',
                        'titre' => 'Explication Vidéo du Concept',
                        'contenu' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', // URL d'intégration vidéo
                        'ordre' => 3
                    ]);

                    SectionCours::create([
                        'id_cours' => $cours->id,
                        'type' => 'img',
                        'titre' => 'Schéma récapitulatif',
                        'contenu' => 'images/formations/schema_recapitulatif.png', // Chemin vers l'image
                        'ordre' => 4
                    ]);

                    SectionCours::create([
                        'id_cours' => $cours->id,
                        'type' => 'upload',
                        'titre' => 'Rendre votre exercice d\'application',
                        'contenu' => 'Veuillez téléverser votre fichier PDF contenant les réponses aux exercices du chapitre.',
                        'ordre' => 5
                    ]);
                }
            }
        }
    }
}