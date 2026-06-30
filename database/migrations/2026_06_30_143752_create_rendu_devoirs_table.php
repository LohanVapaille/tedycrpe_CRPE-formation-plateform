<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rendu_devoirs', function (Blueprint $table) {
            $table->id();
            // ID de la section de type 'upload'
            $table->foreignId('section_cours_id')->constrained('section_cours')->onDelete('cascade');
            // ID de l'étudiant (on suppose que ta table s'appelle 'users')
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Informations sur le fichier rendu
            $table->string('nom_origine');
            $table->string('chemin_fichier'); // Stockera : exercices_rendus/nom_du_fichier.pdf
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rendu_devoirs');
    }
};
