<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccueilContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On nettoie les anciennes données de la page home pour éviter les doublons
        DB::table('contents')->where('page', 'home')->delete();

        DB::table('contents')->insert([
            // ==========================================
            // SECTION : HERO (Le haut de page)
            // ==========================================
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'title',
                'value' => 'Nous formons et outillons celles et ceux qui font l\'avenir de nos enfants.',
                'ordre' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'presentation-tedy',
                'key' => 'subtitle',
                'value' => 'J’aide les adultes à préparer et réussir le CRPE',
                'ordre' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'presentation-tedy',
                'key' => 'subtitle',
                'value' => 'J’aide les adultes à préparer et réussir le CRPE grâce à un coaching personnalisé. La méthode est claire, structurée et rassurante, pour transformer leur manque de temps, leur stress et leur dispersion en une préparation efficace, régulière et guidée vers la réussite.',
                'ordre' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'page' => 'home',
                'section' => 'presentation-tedy',
                'key' => 'photo-tedy',
                'value' => 'img/photo/phototedy.png',
                'ordre' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'page' => 'home',
                'section' => 'presentation-tedy',
                'key' => 'legende-photo-tedy',
                'value' => 'Tedy Florette, formateur CRPE',
                'ordre' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],



            // ==========================================
            // SECTION : STATS (Les 3 compteurs du milieu)
            // ==========================================
            [
                'page' => 'home',
                'section' => 'stats',
                'key' => 'stat_certifies',
                'value' => '245|personnes certifiés', // On peut utiliser un séparateur pipe | pour tricher et stocker chiffre + texte
                'ordre' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'stats',
                'key' => 'stat_modules',
                'value' => '15|modules d\'apprentissage',
                'ordre' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'stats',
                'key' => 'stat_reconnu',
                'value' => '5/5|Un travail apprécié et reconnu',
                'ordre' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================================
            // SECTION : CARDS (Les 4 blocs bleus)
            // ==========================================
            [
                'page' => 'home',
                'section' => 'cards',
                'key' => 'card_1',
                'value' => 'Découvrez les programmes|icone1.png|formation.index', // Titre|Icône|Lien
                'ordre' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'cards',
                'key' => 'card_2',
                'value' => 'S\'inscrire à la formation|icone2.png|formation.index', // Titre|Icône|Lien
                'ordre' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'cards',
                'key' => 'card_3',
                'value' => 'Tedy Florette|icone3.png|formation.index', // Titre|Icône|Lien
                'ordre' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'cards',
                'key' => 'card_4',
                'value' => 'Découvrez les programmes|icone4.png|login', // Titre|Icône|Lien
                'ordre' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================================
            // SECTION : FAQ (Foire aux questions)
            // ==========================================
            [
                'page' => 'home',
                'section' => 'faq',
                'key' => 'faq_1',
                'value' => 'Combien coûte la formation ?|Le tarif de la formation dépend de votre profil et de votre mode de financement (CPF, personnel, ou étalé).', // Question|Réponse
                'ordre' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'faq',
                'key' => 'faq_2',
                'value' => 'Faut-t\'il de l\'expérience ?|Non, aucune expérience préalable dans l\'enseignement n\'est requise. Notre formation reprend les bases pas-à-pas.',
                'ordre' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'faq',
                'key' => 'faq_3',
                'value' => 'Comment se déroule l\'accompagnement ?|L\'accompagnement se fait entièrement en ligne à travers votre espace, combinant des cours et un chat en direct.',
                'ordre' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================================
            // SECTION : FAQ (Foire aux questions)
            // ==========================================
            [
                'page' => 'home',
                'section' => 'avis',
                'key' => 'titre_avis',
                'value' => 'Ce que les élèves pensent de la formation',
                'ordre' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'avis',
                'key' => 'phrase_avis',
                'value' => 'porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam',
                'ordre' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================================
// SECTION : AVIS (Ce que les élèves pensent)
// ==========================================
            [
                'page' => 'home',
                'section' => 'avis-p',
                'key' => 'avis_1',
                'value' => 'Camille P|Tedy a été mon jury pour un oral blanc d\'entretien pour le CRPE M2. Il a pris son rôle au sérieux lors de l\'oral...|5', // Nom|Commentaire|Note
                'ordre' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'avis-p',
                'key' => 'avis_2',
                'value' => 'Elisabeth F|Tedy m\'a apporté une aide concrète, qui m\'a permis d\'améliorer à la fois ma méthode de travail de préparation...|5',
                'ordre' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'avis-p',
                'key' => 'avis_3',
                'value' => 'Vanessa Paradis|La formation s\'est très bien déroulée. porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur...|5',
                'ordre' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'home',
                'section' => 'avis-p',
                'key' => 'avis_4',
                'value' => 'Margaux R|J\'ai été accompagnée dans ma préparation au CRPE et j\'ai beaucoup apprécié la qualité de l\'accompagnement...|5',
                'ordre' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}