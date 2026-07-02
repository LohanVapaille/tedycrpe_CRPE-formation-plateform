<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;

class CrpePrestationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Titre principal de la section
        Content::updateOrCreate(
            [
                'page' => 'crpe',
                'section' => 'prestations',
                'key' => 'titre'
            ],
            [
                'value' => 'Quelles sont les prestations proposées ?',
                'ordre' => 1
            ]
        );

        // 2. Liste complète des prestations fournies
        $prestations = [
            "Coaching visio-conférence sur le déroulé et les attendus des épreuves",
            "Exercices réguliers types concours",
            "Écrit blanc corrigé",
            "Oral blanc corrigé et débriefé",
            "Masterclass en EPS et en CSE",
            "Collaboration avec d'autres formateurs (spécialiste en Français et/ou en Mathématiques)",
            "Accès à la plate-forme de e-learning (cours, capsule vidéo et exercices)",
            "Possibilité d'être auditeur et/ou jury lors d'un oral blanc",
            "Participation à une communauté \"Candidats CRPE\" - Participation à un travail collaboratif",
            "Coaching téléphonique (48 heures avant l'épreuve) pour affiner les détails et maîtriser le stress."
        ];

        // On boucle pour insérer chaque prestation avec une clé unique (etape_1, etape_2, etc.)
        foreach ($prestations as $index => $texte) {
            $ordre = $index + 2; // Commencer l'ordre après le titre (qui est à 1)

            Content::updateOrCreate(
                [
                    'page' => 'crpe',
                    'section' => 'prestations',
                    'key' => 'etape_' . $ordre
                ],
                [
                    'value' => $texte,
                    'ordre' => $ordre
                ]
            );
        }
    }
}