<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chat', function (Blueprint $table) {
            $table->id();

            // Clé étrangère id_user avec contraintes RESTRICT
            $table->foreignId('id_user')
                ->constrained('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat');
    }
};