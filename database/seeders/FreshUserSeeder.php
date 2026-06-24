<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FreshUserSeeder extends Seeder
{
    public function run(): void
    {
        // On supprime d'abord un potentiel ancien compte avec le même email pour éviter les doublons
        User::where('email', 'nouveau@tedycrpe.fr')->delete();

        // Création de l'étudiant avec le mot de passe initial "0000" haché
        User::create([
            'firstname' => 'Chloé',
            'name' => 'Martin',
            'email' => 'nouveau@tedycrpe.fr',
            'password' => Hash::make('0000'), // Hashé en BCRYPT pour être compris par Laravel Auth
            'access' => 'student',
            'is_tutor' => false,
        ]);
    }
}
