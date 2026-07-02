<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // NE PAS OUBLIER CETTE LIGNE

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\Content;

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
                $user = Auth::user();

                if ($user->is_tutor) {
                    // Le tuteur voit par défaut le premier chat ou un chat générique
                    $chat = Chat::first();
                } else {
                    // L'élève récupère ou crée automatiquement son propre chat
                    // Note : vérifie bien si ta colonne en BDD s'appelle id_user ou user_id
                    $chat = Chat::firstOrCreate(['id_user' => $user->id]);
                }

                $view->with('chat', $chat);
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