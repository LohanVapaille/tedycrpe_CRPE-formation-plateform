<?php
// database/seeders/CrpeContentSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CrpeContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contents')->where('page', 'crpe')->delete();

        DB::table('contents')->insert([
            // HERO
            [
                'page' => 'crpe',
                'section' => 'hero',
                'key' => 'titre',
                'value' => 'Comment serez-vous accompagné ?',
                'ordre' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // SECTION FORMATION
            [
                'page' => 'crpe',
                'section' => 'formation',
                'key' => 'titre',
                'value' => 'La formation',
                'ordre' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'formation',
                'key' => 'paragraphe_1',
                'value' => 'Ce programme est spécialement conçu pour les adultes qui ont toujours voulu enseigner et donc passer le concours du CRPE mais ne savent pas par où commencer. Souhaitent progresser à leur rythme, sans pression, avec une méthode concrète et motivante.',
                'ordre' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'formation',
                'key' => 'paragraphe_2',
                'value' => 'Chaque apprenant avance différemment — j\'adapte donc nos séances, mes supports et mon rythme à votre profil et à vos objectifs.',
                'ordre' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'formation',
                'key' => 'btn_texte',
                'value' => 'Voir un aperçu de la formation',
                'ordre' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // SECTION ACCOMPAGNEMENT (étapes)
            [
                'page' => 'crpe',
                'section' => 'accompagnement',
                'key' => 'titre',
                'value' => 'Comment serez-vous accompagné ?',
                'ordre' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'accompagnement',
                'key' => 'etape_1',
                'value' => '1. Évaluation de départ|Je fais le point sur votre niveau, vos besoins et vos objectifs personnels.',
                'ordre' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'accompagnement',
                'key' => 'etape_2',
                'value' => '2. Plan personnalisé|Vous recevez un parcours sur mesure, adapté à vos disponibilités et à votre style d\'apprentissage.',
                'ordre' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'accompagnement',
                'key' => 'etape_3',
                'value' => '3. Séances interactives|Des cours vivants, pratiques et motivants, pour progresser rapidement.',
                'ordre' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'accompagnement',
                'key' => 'etape_4',
                'value' => '4. Suivi régulier|Nous ajustons ensemble les contenus pour que chaque étape vous rapproche de vos objectifs.',
                'ordre' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // SECTION PROGRESSION
            [
                'page' => 'crpe',
                'section' => 'progression',
                'key' => 'titre',
                'value' => 'Une progression assurée ?',
                'ordre' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'progression',
                'key' => 'paragraphe',
                'value' => 'Grâce à mon accompagnement pas à pas, vous allez comprendre les notions clés facilement, gagner en confiance et vous exercer avec des épreuves blanches. Notre approche vous garantit une maîtrise progressive et durable des connaissances en lien avec l\'examen.',
                'ordre' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'progression',
                'key' => 'etape_1',
                'value' => '📖|Cours Magistraux|border-orange',
                'ordre' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'progression',
                'key' => 'etape_2',
                'value' => '💻|E-learning platform|border-red',
                'ordre' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'progression',
                'key' => 'etape_3',
                'value' => '💬|Espace d\'échange|border-purple',
                'ordre' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'progression',
                'key' => 'etape_4',
                'value' => '👥|Suivi individuel|border-blue',
                'ordre' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'crpe',
                'section' => 'progression',
                'key' => 'etape_5',
                'value' => '📝|Examens blancs|border-green',
                'ordre' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}