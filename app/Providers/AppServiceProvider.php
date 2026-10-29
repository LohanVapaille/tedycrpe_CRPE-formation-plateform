<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // NE PAS OUBLIER CETTE LIGNE

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\Content;
use App\Models\Message;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force la longueur par défaut des string à 191 pour éviter l'erreur d'index de MySQL
        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {
            if (Auth::check()) {
                // once() : une seule série de requêtes par page, même si le composer
                // est appelé pour chaque vue et partial
                $data = once(function () {
                    $user = Auth::user();

                    if ($user->is_tutor) {
                        // Le tuteur voit par défaut le premier chat ou un chat générique
                        $chat = Chat::first();
                    } else {
                        // L'élève récupère ou crée automatiquement son propre chat
                        $chat = Chat::firstOrCreate(['id_user' => $user->id]);
                    }

                    return [
                        'chat' => $chat,
                        'chatUnread' => Message::unreadFor($user)->count(),
                    ];
                });

                $view->with($data);
            }
        });

        if (Schema::hasTable('contents')) {
            // Récupère toutes les clés du footer sous forme de tableau clé => valeur
            $footerSettings = Content::where('key', 'like', 'footer_%')
                ->pluck('value', 'key')
                ->toArray();

            // Rend la variable $footerSettings accessible dans toutes les vues Blade
            View::share('footerSettings', $footerSettings);
        }
    }
}