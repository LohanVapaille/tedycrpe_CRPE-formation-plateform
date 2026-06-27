<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Content;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        // Contenu pour la page "Qui suis-je ?"
        Content::create([
            'page' => 'qui-suis-je',
            'section' => 'presentation', // AJOUTÉ ICI
            'key' => 'titre_principal',
            'value' => 'Qui suis-je ?'
        ]);

        Content::create([
            'page' => 'qui-suis-je',
            'section' => 'presentation', // AJOUTÉ ICI
            'key' => 'photo_profil',
            'value' => 'img/photo/phototedyflorette_carree.jpg'
        ]);

        Content::create([
            'page' => 'qui-suis-je',
            'section' => 'presentation', // AJOUTÉ ICI
            'key' => 'paragraphe_1',
            'value' => 'Mon approche est spécialement conçue pour les adultes qui ont toujours voulu enseigner et passer le concours du CRPE, mais qui ne savent pas par où commencer. Je mets un point d’honneur à rendre l’apprentissage simple, motivant et parfaitement adapté au rythme de la vie d’adulte.'
        ]);

        Content::create([
            'page' => 'qui-suis-je',
            'section' => 'presentation', // AJOUTÉ ICI
            'key' => 'paragraphe_2',
            'value' => 'Chaque apprenant avance différemment. C’est pourquoi j’adapte entièrement mes séances, mes supports de cours et mon rythme en fonction de votre profil et de vos objectifs personnels. Grâce à cet accompagnement pas à pas, vous allez comprendre les notions clés facilement et gagner en confiance.'
        ]);

        Content::create([
            'page' => 'qui-suis-je',
            'section' => 'presentation', // AJOUTÉ ICI
            'key' => 'paragraphe_3',
            'value' => 'Au-delà des cours magistraux sur Zoom et de l’accès à une plateforme de e-learning, l’objectif est de construire une progression concrète, mesurable et gratifiante à travers des examens blancs réguliers pour vous amener avec sérénité jusqu’à l’obtention de votre CRPE.'
        ]);
    }
}
