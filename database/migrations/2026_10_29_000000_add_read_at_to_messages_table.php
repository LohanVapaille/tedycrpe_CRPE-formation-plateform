<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Date de lecture par le destinataire (null = non lu)
            $table->timestamp('read_at')->nullable()->after('id_chat');
            $table->index(['id_chat', 'read_at']);
        });

        // Les messages existants sont considérés comme lus
        DB::table('messages')->update(['read_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['id_chat', 'read_at']);
            $table->dropColumn('read_at');
        });
    }
};
