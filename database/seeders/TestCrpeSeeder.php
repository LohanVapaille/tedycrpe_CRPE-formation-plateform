<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Matiere;
use App\Models\Cours;
use App\Models\Content;
use App\Models\SectionCours;
use Illuminate\Support\Facades\Hash;

class TestCrpeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création d'un compte Prof/Tuteur et Éléve pour tester l'auth
        $tuteur = User::create([
            'firstname' => 'Ramata',
            'name' => 'Prause',
            'email' => 'tuteur@tedycrpe.fr',
            'password' => Hash::make('password123'),
            'access' => 'admin',
            'is_tutor' => true,
        ]);

        $eleve = User::create([
            'firstname' => 'Rémi',
            'name' => 'Dupont',
            'email' => 'eleve@tedycrpe.fr',
            'password' => Hash::make('password123'),
            'access' => 'student',
            'is_tutor' => false,
        ]);

        // 2. Création d'une première Matière
        $maths = Matiere::create([
            'name' => 'Mathématiques',
            'module_associe' => 'CRPE Bac +5',
            'ordre' => 1,
        ]);

        // 2. Création d'une première Matière
        $maths = Matiere::create([
            'name' => 'Blabla',
            'module_associe' => 'CRPE Bac +5',
            'ordre' => 2,
        ]);

        // 3. Création d'un Cours lié aux Maths
        $geometrie = Cours::create([
            'name' => 'Géométrie Plane',
            'ordre' => 1,
            'id_matiere' => $maths->id,
        ]);

        // 4. Création de quelques Sections de révision pour ce cours
        SectionCours::create([
            'type' => 'cours',
            'titre' => 'Les triangles et leurs propriétés',
            'contenu' => 'Un triangle isocèle possède deux côtés égaux...',
            'ordre' => 1,
            'id_cours' => $geometrie->id,
        ]);



        SectionCours::create([
            'type' => 'video',
            'titre' => 'Comprendre le Théorème de Pythagore',
            'contenu' => 'Dans un triangle rectangle, le carré de l\'hypoténuse...',
            'ordre' => 2,
            'id_cours' => $geometrie->id,
        ]);

        SectionCours::create([
            'type' => 'quiz',
            'titre' => 'Quiz d\'auto-évaluation : Les Bases',
            'contenu' => 'Question 1 : Quelle est la somme des angles d\'un triangle ?',
            'ordre' => 3,
            'id_cours' => $geometrie->id,
        ]);

        // Contenu de la page Accueil
        Content::create(['page' => 'home', 'section' => 'hero', 'key' => 'title', 'value' => 'Nous formons et outillons celles et ceux qui font l’avenir de nos enfants.']);
        Content::create(['page' => 'home', 'section' => 'presentation', 'key' => 'body', 'value' => 'J’aide les adultes à préparer et réussir le CRPE...']);

        // Contenu de la page Qui suis-je
        Content::create(['page' => 'about', 'section' => 'bio', 'key' => 'title', 'value' => 'Qui est Tedy Floretto ?']);
        Content::create(['page' => 'about', 'section' => 'bio', 'key' => 'body', 'value' => 'Formateur passionné depuis des années...']);
    }
}