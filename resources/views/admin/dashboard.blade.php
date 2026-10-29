{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')
@section('title', 'Tableau de bord')

@php
    // Couleur associée à chaque formule (barres de répartition)
    $couleurs = [
        'CRPE BAC + 3' => '#3b82f6',
        'CRPE BAC + 3 E-learning' => '#93c5fd',
        'CRPE BAC + 5' => '#8b5cf6',
        'CRPE BAC + 5 E-learning' => '#c4b5fd',
        '3ème voie/Interne' => '#f59e0b',
        'all' => '#94a3b8',
    ];
    $max = max(1, $formules->max('total'));
    $echeanceTexte = $maintenance['joursRestants'] === 0
        ? "aujourd'hui"
        : ($maintenance['joursRestants'] === 1 ? 'demain' : 'dans ' . $maintenance['joursRestants'] . ' jours');
@endphp

@push('styles')
    <style>
        .dash-grid {
            display: grid;
            gap: 1.25rem;
        }

        .kpi-grid {
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            margin-bottom: 1.25rem;
        }

        .kpi {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: inherit;
            transition: transform 0.15s, box-shadow 0.15s;
        }

        a.kpi:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
            color: inherit;
        }

        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .kpi-value {
            font-size: 1.7rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .kpi-label {
            font-size: 0.82rem;
            color: var(--admin-muted);
        }

        .dash-cols {
            grid-template-columns: 1.4fr 1fr;
            margin-bottom: 1.25rem;
        }

        .dash-cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .card-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 1.1rem;
        }

        .card-title a {
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
        }

        /* Répartition par formule */
        .formule {
            margin-bottom: 0.9rem;
        }

        .formule-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.88rem;
            margin-bottom: 0.35rem;
        }

        .formule-head strong {
            font-size: 0.95rem;
        }

        .formule-bar {
            height: 10px;
            border-radius: 5px;
            background: #f1f5f9;
            overflow: hidden;
        }

        .formule-bar span {
            display: block;
            height: 100%;
            border-radius: 5px;
        }

        .split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--admin-border);
        }

        .split-item {
            background: #f8fafc;
            border-radius: 8px;
            padding: 0.8rem 1rem;
        }

        .split-item strong {
            display: block;
            font-size: 1.3rem;
        }

        .split-item span {
            font-size: 0.8rem;
            color: var(--admin-muted);
        }

        /* Listes compactes */
        .dash-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .dash-list li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
        }

        .dash-list li:last-child {
            border-bottom: none;
        }

        .dash-list .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--admin-brand-soft);
            color: var(--admin-brand);
            font-weight: 600;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dash-list .grow {
            flex: 1;
            min-width: 0;
        }

        .dash-list .grow span {
            display: block;
            font-size: 0.78rem;
            color: var(--admin-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dash-list .date-chip {
            width: 44px;
            text-align: center;
            border-radius: 8px;
            background: #f1f5f9;
            padding: 0.25rem 0;
            line-height: 1.1;
            flex-shrink: 0;
        }

        .dash-list .date-chip strong {
            display: block;
            font-size: 1rem;
        }

        .dash-list .date-chip small {
            font-size: 0.65rem;
            text-transform: uppercase;
            color: var(--admin-muted);
        }

        .dash-empty {
            color: var(--admin-muted);
            font-size: 0.88rem;
            text-align: center;
            padding: 1.5rem 0;
        }

        /* Maintenance */
        .maintenance {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 1.5rem;
            scroll-margin-top: 80px;
        }

        .maintenance-pay {
            background: linear-gradient(135deg, var(--admin-brand), #1e4f58);
            color: white;
            border-radius: var(--admin-radius);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .maintenance-pay .label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        .maintenance-pay .price {
            font-size: 2.4rem;
            font-weight: 700;
            line-height: 1;
        }

        .maintenance-pay .price small {
            font-size: 1rem;
            font-weight: 500;
            opacity: 0.85;
        }

        .maintenance-pay .due {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 0.9rem;
        }

        .maintenance-pay .due strong {
            display: block;
            font-size: 1.05rem;
        }

        .maintenance-pay.is-soon {
            background: linear-gradient(135deg, #d97706, #b45309);
        }

        .maintenance-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.9rem 1.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .maintenance-list li {
            display: flex;
            gap: 0.75rem;
            font-size: 0.85rem;
            color: var(--admin-muted);
        }

        .maintenance-list li i {
            font-size: 1.3rem;
            color: var(--admin-brand);
            background: var(--admin-brand-soft);
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .maintenance-list li strong {
            display: block;
            color: var(--admin-text);
            font-size: 0.9rem;
        }

        @media (max-width: 1100px) {

            .dash-cols,
            .dash-cols-3,
            .maintenance {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .maintenance-list {
                grid-template-columns: 1fr;
            }

            .kpi-grid {
                grid-template-columns: 1fr 1fr;
            }

            .kpi {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.6rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="admin-header">
        <div>
            <h1>Bonjour {{ Auth::user()->firstname }} 👋</h1>
            <p class="admin-subtitle">Voici l'activité de votre plateforme au
                {{ now()->locale('fr')->translatedFormat('l j F Y') }}.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class='bx bx-user-plus'></i> Nouvel élève
        </a>
    </div>

    @if($maintenance['rappel'])
        <div class="admin-alert is-warning">
            <i class='bx bx-credit-card'></i>
            <div>
                <strong>Rappel :</strong> la maintenance {{ $maintenance['nom'] }} de
                <strong>{{ $maintenance['prix'] }} €</strong> est à régler {{ $echeanceTexte }}
                ({{ $maintenance['echeance']->locale('fr')->translatedFormat('l j F') }}).
            </div>
        </div>
    @endif

    {{-- Chiffres clés --}}
    <div class="dash-grid kpi-grid">
        <a href="{{ route('admin.users') }}" class="card kpi">
            <div class="kpi-icon" style="background:#e6f1f3; color:#2c6e7a;"><i class='bx bx-group'></i></div>
            <div>
                <div class="kpi-value">{{ $stats['total'] }}</div>
                <div class="kpi-label">Élèves inscrits</div>
            </div>
        </a>
        <div class="card kpi">
            <div class="kpi-icon" style="background:#dcfce7; color:#16a34a;"><i class='bx bx-user-plus'></i></div>
            <div>
                <div class="kpi-value">{{ $stats['nouveaux'] }}</div>
                <div class="kpi-label">Nouveaux (30 jours)</div>
            </div>
        </div>
        <button type="button" class="card kpi" onclick="document.getElementById('open-channels-btn')?.click()"
            style="text-align:left; cursor:pointer;">
            <div class="kpi-icon" style="background:#fee2e2; color:#dc2626;"><i class='bx bx-message-dots'></i></div>
            <div>
                <div class="kpi-value">{{ $stats['chatNonLus'] }}</div>
                <div class="kpi-label">Messages élèves non lus</div>
            </div>
        </button>
        <a href="{{ route('admin.messages.index') }}" class="card kpi">
            <div class="kpi-icon" style="background:#fef3c7; color:#b45309;"><i class='bx bx-envelope'></i></div>
            <div>
                <div class="kpi-value">{{ $stats['emailsNonLus'] }}</div>
                <div class="kpi-label">Emails de contact non lus</div>
            </div>
        </a>
        <a href="{{ route('admin.devoirs.index') }}" class="card kpi">
            <div class="kpi-icon" style="background:#ede9fe; color:#6d28d9;"><i class='bx bxs-book-content'></i></div>
            <div>
                <div class="kpi-value">{{ $stats['rendusSemaine'] }}</div>
                <div class="kpi-label">Devoirs rendus (7 jours) · {{ $stats['rendus'] }} au total</div>
            </div>
        </a>
    </div>

    <div class="dash-grid dash-cols">
        {{-- Répartition des élèves --}}
        <div class="card">
            <h2 class="card-title">Élèves par formule</h2>

            @foreach($formules as $valeur => $formule)
                <div class="formule">
                    <div class="formule-head">
                        <span>
                            {{ $formule['libelle'] }}
                            @if($formule['elearning'])
                                <span class="pill is-amber">E-learning</span>
                            @endif
                        </span>
                        <strong>{{ $formule['total'] }}</strong>
                    </div>
                    <div class="formule-bar">
                        <span
                            style="width: {{ $formule['total'] ? max(2, round($formule['total'] / $max * 100)) : 0 }}%; background: {{ $couleurs[$valeur] ?? '#94a3b8' }};"></span>
                    </div>
                </div>
            @endforeach

            <div class="split">
                <div class="split-item">
                    <strong>{{ $stats['bac3'] }}</strong>
                    <span>CRPE BAC +3 (avec e-learning)</span>
                </div>
                <div class="split-item">
                    <strong>{{ $stats['bac5'] }}</strong>
                    <span>CRPE BAC +5 (avec e-learning)</span>
                </div>
                <div class="split-item">
                    <strong>{{ $stats['accompagnement'] }}</strong>
                    <span>Avec accompagnement</span>
                </div>
                <div class="split-item">
                    <strong>{{ $stats['elearning'] }}</strong>
                    <span>E-learning seul</span>
                </div>
            </div>
        </div>

        {{-- Derniers inscrits --}}
        <div class="card">
            <h2 class="card-title">
                Derniers inscrits
                <a href="{{ route('admin.users') }}">Tout voir →</a>
            </h2>
            @if($derniersEleves->isEmpty())
                <p class="dash-empty">Aucun élève pour le moment.</p>
            @else
                <ul class="dash-list">
                    @foreach($derniersEleves as $eleve)
                        <li>
                            <div class="avatar">
                                {{ strtoupper(mb_substr($eleve->firstname, 0, 1) . mb_substr($eleve->name, 0, 1)) }}
                            </div>
                            <div class="grow">
                                <a href="{{ route('admin.users.edit', $eleve) }}"
                                    class="text-decoration-none text-dark fw-semibold">{{ $eleve->firstname }}
                                    {{ $eleve->name }}</a>
                                <span>{{ \App\Models\User::ACCES[$eleve->access] ?? $eleve->access }}</span>
                            </div>
                            <small class="text-muted">{{ $eleve->created_at?->locale('fr')->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="dash-grid dash-cols" style="grid-template-columns: 1fr 1fr;">
        {{-- Derniers devoirs --}}
        <div class="card">
            <h2 class="card-title">
                Derniers devoirs rendus
                <a href="{{ route('admin.devoirs.index') }}">Tout voir →</a>
            </h2>
            @if($derniersRendus->isEmpty())
                <p class="dash-empty">Aucun devoir rendu pour le moment.</p>
            @else
                <ul class="dash-list">
                    @foreach($derniersRendus as $rendu)
                        <li>
                            <div class="avatar" style="background:#ede9fe; color:#6d28d9;"><i class='bx bx-file'></i></div>
                            <div class="grow">
                                {{ $rendu->student?->firstname }} {{ $rendu->student?->name }}
                                <span>{{ $rendu->section?->cours?->name ?? 'Cours inconnu' }}</span>
                            </div>
                            <a href="{{ route('admin.devoirs.download', $rendu) }}" class="btn btn-secondary"
                                title="Télécharger" style="padding: 0.35rem 0.6rem;">
                                <i class='bx bx-download'></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Prochains événements --}}
        <div class="card">
            <h2 class="card-title">
                Prochains événements
                <a href="{{ route('admin.evenements') }}">Tout voir →</a>
            </h2>
            @if($prochainsEvenements->isEmpty())
                <p class="dash-empty">Aucun événement à venir.</p>
            @else
                <ul class="dash-list">
                    @foreach($prochainsEvenements as $event)
                        @php $debut = $event->start_time->locale('fr'); @endphp
                        <li>
                            <div class="date-chip">
                                <strong>{{ $debut->format('d') }}</strong>
                                <small>{{ $debut->translatedFormat('M') }}</small>
                            </div>
                            <div class="grow">
                                {{ $event->title }}
                                <span>
                                    {{ $debut->format('H:i') }} ·
                                    {{ $event->user ? '🔒 ' . $event->user->firstname . ' ' . $event->user->name : '🌐 Public' }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Maintenance --}}
    <div class="card" id="maintenance">
        <h2 class="card-title">
            Maintenance du site
            <span class="pill is-brand"><i class='bx bx-check-shield'></i> Abonnement actif</span>
        </h2>

        <div class="maintenance">
            <div class="maintenance-pay {{ $maintenance['rappel'] ? 'is-soon' : '' }}">
                <span class="label">{{ $maintenance['nom'] }}</span>
                <span class="price">{{ $maintenance['prix'] }} € <small>/ mois</small></span>
                <span>À régler le dernier jour de chaque mois.</span>
                <div class="due">
                    Prochain paiement {{ $echeanceTexte }}
                    <strong>{{ ucfirst($maintenance['echeance']->locale('fr')->translatedFormat('l j F Y')) }}</strong>
                </div>
            </div>

            <ul class="maintenance-list">
                @foreach($maintenance['inclus'] as [$icone, $titre, $detail])
                    <li>
                        <i class='bx {{ $icone }}'></i>
                        <div>
                            <strong>{{ $titre }}</strong>
                            {{ $detail }}
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
