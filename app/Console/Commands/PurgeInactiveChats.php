<?php

namespace App\Console\Commands;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Supprime les conversations inactives et leurs messages.
 *
 * Une conversation est inactive quand son dernier message (ou, à défaut de message,
 * sa création) date de plus de N jours. Le chat d'un élève est recréé automatiquement
 * à sa prochaine visite (firstOrCreate), la suppression est donc sans conséquence.
 */
class PurgeInactiveChats extends Command
{
    protected $signature = 'chat:purge-inactive
                            {--days= : Jours d\'inactivité (par défaut : config chat.inactive_after_days)}
                            {--dry-run : Affiche ce qui serait supprimé sans rien supprimer}';

    protected $description = 'Supprime les conversations inactives et leurs messages';

    public function handle(): int
    {
        $days = (int) ($this->option('days') ?? config('chat.inactive_after_days'));

        if ($days <= 0) {
            $this->info('Purge désactivée (durée d\'inactivité à 0).');
            return self::SUCCESS;
        }

        $limit = now()->subDays($days);

        // Dernière activité = dernier message, sinon date de création du chat
        $chatIds = Chat::query()
            ->leftJoin('messages', 'messages.id_chat', '=', 'chat.id')
            ->groupBy('chat.id', 'chat.created_at')
            ->havingRaw('COALESCE(MAX(messages.created_at), chat.created_at) < ?', [$limit])
            ->pluck('chat.id');

        $messagesCount = Message::whereIn('id_chat', $chatIds)->count();

        if ($chatIds->isEmpty()) {
            $this->info("Aucune conversation inactive depuis plus de {$days} jours.");
            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->info("[Simulation] {$chatIds->count()} conversation(s) et {$messagesCount} message(s) seraient supprimés (inactifs depuis le {$limit->format('d/m/Y')}).");
            return self::SUCCESS;
        }

        // Les messages d'abord : la clé étrangère messages.id_chat est en RESTRICT
        $chatIds->chunk(500)->each(function ($ids) {
            DB::transaction(function () use ($ids) {
                Message::whereIn('id_chat', $ids)->delete();
                Chat::whereIn('id', $ids)->delete();
            });
        });

        $this->info("{$chatIds->count()} conversation(s) et {$messagesCount} message(s) supprimés.");

        return self::SUCCESS;
    }
}
