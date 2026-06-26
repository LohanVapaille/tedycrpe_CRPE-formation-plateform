<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sections_cours', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // titre, txt, img, video, upload
            $table->string('titre')->nullable(); // <-- Devient optionnel
            $table->text('contenu')->nullable(); // <-- Devient optionnel
            $table->integer('ordre');
            $table->foreignId('id_cours')->constrained('cours')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections_cours');
    }
};