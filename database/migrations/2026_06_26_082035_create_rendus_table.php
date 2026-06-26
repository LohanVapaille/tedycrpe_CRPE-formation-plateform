<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rendus', function (Blueprint $table) {
            $table->id();
            // L'utilisateur qui envoie le fichier
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');

            // Optionnel : L'ID du cours concerné par le devoir (si tu veux lier le devoir à un cours)
            $table->foreignId('id_cours')->nullable()->constrained('cours')->onDelete('cascade');

            // Les informations du fichier stocké
            $table->string('nom_origine'); // Exemple : "devoir_maths.pdf"
            $table->string('chemin_fichier'); // Le chemin de stockage sur ton serveur

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendus');
    }
};
