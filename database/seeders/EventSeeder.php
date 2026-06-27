<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On récupère le premier utilisateur (élève) pour lui assigner les événements privés
        // Si tu n'as pas encore d'utilisateurs, assure-toi d'exécuter ton UserSeeder avant celui-ci.
        $eleve = User::first();

        $now = Carbon::now();

        $events = [
            // ==========================================
            // ÉVÉNEMENTS PUBLICS (visibles par tous)
            // ==========================================
            [
                'title' => 'Masterclass #1 : Stratégie de préparation au CRPE',
                'description' => 'Découverte de la méthode structurée pour aborder sereinement les différentes épreuves.',
                'type' => 'masterclass',
                'start_time' => $now->copy()->addDays(1)->setTime(18, 0, 0),
                'end_time' => $now->copy()->addDays(1)->setTime(20, 0, 0),
                'user_id' => null, // Public
            ],
            [
                'title' => 'Visio-Conférence : Session Questions / Réponses',
                'description' => 'Un moment d\'échange collectif sur Zoom pour faire le point sur vos blocages actuels.',
                'type' => 'visio',
                'start_time' => $now->copy()->addDays(3)->setTime(14, 0, 0),
                'end_time' => $now->copy()->addDays(3)->setTime(15, 30, 0),
                'user_id' => null, // Public
            ],
            [
                'title' => 'Concours Blanc : Épreuves Écrites',
                'description' => 'Mise en situation réelle sur les épreuves de Français et de Mathématiques.',
                'type' => 'ecrit_blanc',
                'start_time' => $now->copy()->addDays(7)->setTime(9, 0, 0),
                'end_time' => $now->copy()->addDays(7)->setTime(13, 0, 0),
                'user_id' => null, // Public
            ],
            [
                'title' => 'Simulations : Épreuves Orales d\'Entretien',
                'description' => 'Passages collectifs pour analyser les grilles d\'évaluation des oraux blancs.',
                'type' => 'oral_blanc',
                'start_time' => $now->copy()->addDays(10)->setTime(10, 0, 0),
                'end_time' => $now->copy()->addDays(10)->setTime(17, 0, 0),
                'user_id' => null, // Public
            ],
            [
                'title' => 'Dates Officielles : Ouverture des Inscriptions au Concours',
                'description' => 'Date limite nationale pour le dépôt des dossiers de candidature au CRPE.',
                'type' => 'officiel',
                'start_time' => $now->copy()->addDays(14)->setTime(8, 0, 0),
                'end_time' => $now->copy()->addDays(14)->setTime(18, 0, 0),
                'user_id' => null, // Public
            ],
        ];

        // ==========================================
        // ÉVÉNEMENTS PRIVÉS (uniquement pour un élève)
        // ==========================================
        if ($eleve) {
            $events[] = [
                'title' => 'Entretien Individuel : Bilan d\'Évaluation de Départ',
                'description' => 'Rendez-vous personnalisé privé pour faire le point sur votre niveau, vos besoins et co-construire votre plan sur mesure.',
                'type' => 'prive',
                'start_time' => $now->copy()->addDays(2)->setTime(11, 0, 0),
                'end_time' => $now->copy()->addDays(2)->setTime(12, 0, 0),
                'user_id' => $eleve->id, // Privé : Seul cet élève le verra !
            ];

            $events[] = [
                'title' => 'Coaching Privé : Accompagnement Méthodologique',
                'description' => 'Session de suivi individuelle sur les points complexes des épreuves.',
                'type' => 'prive',
                'start_time' => $now->copy()->addDays(5)->setTime(16, 0, 0),
                'end_time' => $now->copy()->addDays(5)->setTime(17, 0, 0),
                'user_id' => $eleve->id, // Privé : Seul cet élève le verra !
            ];
        }

        // Insertion en base de données
        foreach ($events as $eventData) {
            Event::create($eventData);
        }
    }
}