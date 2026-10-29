<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('matieres', function (Blueprint $table) {
            // Module ouvert aux élèves ayant l'accès "3ème voie/Interne"
            $table->boolean('acces_troisieme_voie')->default(false)->after('bloc_id');
        });

        // Accès demandé : l'oral de leçon Bac +5 et l'oral 2 Bac +3
        DB::table('matieres')
            ->where(fn($q) => $q->where('module_associe', 'CRPE BAC + 5')->where('name', 'like', '%Leçon%'))
            ->orWhere(fn($q) => $q->where('module_associe', 'CRPE BAC + 3')->where('name', 'like', '%Oral 2%'))
            ->update(['acces_troisieme_voie' => true]);
    }

    public function down(): void
    {
        Schema::table('matieres', function (Blueprint $table) {
            $table->dropColumn('acces_troisieme_voie');
        });
    }
};
