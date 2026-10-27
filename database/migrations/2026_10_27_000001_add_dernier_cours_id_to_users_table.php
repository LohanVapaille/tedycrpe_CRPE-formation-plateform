<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Dernier cours ouvert par l'élève, pour le bouton "Accéder à ma formation"
            $table->foreignId('dernier_cours_id')->nullable()->constrained('cours')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dernier_cours_id');
        });
    }
};
