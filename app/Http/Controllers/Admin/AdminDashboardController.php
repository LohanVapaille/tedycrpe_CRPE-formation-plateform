<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\Message;
use App\Models\RenduDevoir;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Pack de maintenance souscrit : 49 €/mois, à régler le dernier jour de chaque mois.
     */
    public const MAINTENANCE = [
        'nom' => 'Pack Essentiel',
        'prix' => 49,
        'inclus' => [
            ['bx-data', 'Sauvegarde quotidienne de la base de données', 'Comptes, progression et messages des élèves copiés chaque jour, conservés 30 jours.'],
            ['bx-server', 'Sauvegarde complète hebdomadaire du serveur', 'Stockée à part : même en cas de panne du serveur, rien n\'est perdu.'],
            ['bx-shield-quarter', 'Mises à jour de sécurité', 'PHP, Laravel et modules à jour (protection contre les failles type « PHP2Shell »).'],
            ['bx-pulse', 'Surveillance du site', 'Alerte immédiate si la plateforme ne répond plus.'],
            ['bx-lock-alt', 'Certificat SSL', 'Le site reste sécurisé (cadenas dans le navigateur).'],
            ['bx-bug', 'Correction des bugs bloquants', 'Sous 72 h ouvrées.'],
        ],
    ];

    public function index()
    {
        $eleves = User::where('is_tutor', false);

        // Nombre d'élèves par formule (une seule requête)
        $parAcces = (clone $eleves)
            ->select('access', DB::raw('COUNT(*) as total'))
            ->groupBy('access')
            ->pluck('total', 'access');

        $formules = collect(User::ACCES)->map(fn($libelle, $valeur) => [
            'libelle' => $libelle,
            'total' => (int) ($parAcces[$valeur] ?? 0),
            'elearning' => array_key_exists($valeur, User::ACCES_ELEARNING),
        ]);

        $totalEleves = $parAcces->sum();

        $stats = [
            'total' => $totalEleves,
            'bac3' => (int) (($parAcces['CRPE BAC + 3'] ?? 0) + ($parAcces['CRPE BAC + 3 E-learning'] ?? 0)),
            'bac5' => (int) (($parAcces['CRPE BAC + 5'] ?? 0) + ($parAcces['CRPE BAC + 5 E-learning'] ?? 0)),
            'elearning' => (int) $parAcces->only(array_keys(User::ACCES_ELEARNING))->sum(),
            'nouveaux' => (clone $eleves)->where('created_at', '>=', now()->subDays(30))->count(),
            'chatNonLus' => Message::unreadFor(Auth::user())->count(),
            'emailsNonLus' => ContactMessage::where('is_read', false)->count(),
            'rendus' => RenduDevoir::count(),
            'rendusSemaine' => RenduDevoir::where('created_at', '>=', now()->subDays(7))->count(),
        ];
        $stats['accompagnement'] = $totalEleves - $stats['elearning'];

        $derniersEleves = (clone $eleves)->latest()->take(5)->get(['id', 'firstname', 'name', 'access', 'created_at']);

        $derniersRendus = RenduDevoir::with(['student:id,firstname,name', 'section.cours:id,name'])
            ->latest()->take(5)->get();

        $prochainsEvenements = Event::with('user:id,firstname,name')
            ->where('start_time', '>=', now())
            ->orderBy('start_time')->take(5)->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'formules' => $formules,
            'derniersEleves' => $derniersEleves,
            'derniersRendus' => $derniersRendus,
            'prochainsEvenements' => $prochainsEvenements,
            'maintenance' => self::MAINTENANCE + self::echeance(),
        ]);
    }

    /**
     * Prochaine échéance de la maintenance (dernier jour du mois en cours).
     */
    public static function echeance(): array
    {
        $date = Carbon::today()->endOfMonth()->startOfDay();
        $jours = (int) Carbon::today()->diffInDays($date);

        return [
            'echeance' => $date,
            'joursRestants' => $jours,
            // Rappel affiché dans tout le backoffice les 5 derniers jours du mois
            'rappel' => $jours <= 5,
        ];
    }
}
