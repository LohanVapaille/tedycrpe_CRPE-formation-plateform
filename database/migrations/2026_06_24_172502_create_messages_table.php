<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('contenu'); // Corrigé

            // Clé étrangère id_writer vers users
            $table->foreignId('id_writer')
                ->constrained('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            // Clé étrangère id_chat vers chat
            $table->foreignId('id_chat')
                ->constrained('chat')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->timestamps(); // Gère le champ created_at automatiquement
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};