<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Blocs de regroupement des modules dans l'espace élève (Leçon, EPS, Entretien...), gérés dans l'admin
        Schema::create('blocs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });

        Schema::table('matieres', function (Blueprint $table) {
            $table->foreignId('bloc_id')->nullable()->after('module_associe')->constrained('blocs')->nullOnDelete();
        });

        // Blocs de départ + rattachement des modules d'après leur nom
        // ("11 · Entretien EPS · ..." -> EPS, "10 · Entretien · ..." -> Entretien, "01 · Leçon · ..." -> Leçon)
        $ids = [];
        foreach (['Leçon', 'EPS', 'Entretien'] as $i => $nom) {
            $ids[$nom] = DB::table('blocs')->insertGetId([
                'name' => $nom,
                'ordre' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('matieres')->where('name', 'like', '%EPS%')->update(['bloc_id' => $ids['EPS']]);
        DB::table('matieres')->whereNull('bloc_id')->where('name', 'like', '%Entretien%')->update(['bloc_id' => $ids['Entretien']]);
        DB::table('matieres')->whereNull('bloc_id')->where('name', 'like', '%Leçon%')->update(['bloc_id' => $ids['Leçon']]);
    }

    public function down(): void
    {
        Schema::table('matieres', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bloc_id');
        });

        Schema::dropIfExists('blocs');
    }
};
