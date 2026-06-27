<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();

            // Les différents types d'événements
            // 'masterclass', 'visio', 'ecrit_blanc', 'oral_blanc', 'officiel', 'prive'
            $table->string('type');

            $table->dateTime('start_time');
            $table->dateTime('end_time');

            // Si user_id est NULL -> l'événement est PUBLIC (visible par tous les élèves)
            // Si user_id contient un ID -> l'événement est PRIVÉ (visible uniquement par cet élève)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};