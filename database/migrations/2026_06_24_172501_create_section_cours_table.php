<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sections_cours', function (Blueprint $table) {
            $table->id();
            $table->string('type', 100);
            $table->string('titre', 100);
            $table->text('contenu');
            $table->integer('ordre');

            // Clé étrangère id_cours avec contraintes RESTRICT
            $table->foreignId('id_cours')
                ->constrained('cours')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections_cours');
    }
};