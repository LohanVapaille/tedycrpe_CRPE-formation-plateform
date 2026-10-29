{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – @yield('title')</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Tom Select : listes déroulantes avec recherche -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --admin-bg: #f4f6f9;
            --admin-surface: #ffffff;
            --admin-border: #e2e8f0;
            --admin-text: #1e293b;
            --admin-muted: #64748b;
            --admin-sidebar: #1e293b;
            --admin-sidebar-hover: #334155;
            --admin-brand: #2c6e7a;
            --admin-brand-dark: #225963;
            --admin-brand-soft: #e6f1f3;
            --admin-primary: #3b82f6;
            --admin-primary-dark: #2563eb;
            --admin-danger: #ef4444;
            --admin-success: #16a34a;
            --admin-warning: #f59e0b;
            --admin-radius: 10px;
            --admin-shadow: 0 1px 3px rgba(15, 23, 42, 0.06), 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--admin-bg);
            color: var(--admin-text);
            overflow-x: hidden;
        }

        /* --- BARRE MOBILE --- */
        .admin-topbar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            padding: 0 1rem;
            background: var(--admin-sidebar);
            color: white;
            align-items: center;
            gap: 0.75rem;
            z-index: 997;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .admin-topbar .topbar-title {
            flex: 1;
            font-weight: 600;
            font-size: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mobile-toggle,
        .topbar-site {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .topbar-site {
            font-size: 1.3rem;
        }

        .mobile-toggle:hover,
        .topbar-site:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* --- SIDEBAR --- */
        .admin-sidebar {
            width: 250px;
            background: var(--admin-sidebar);
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 999;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.5rem 1.25rem 1rem;
        }

        .sidebar-brand img {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: white;
            padding: 4px;
        }

        .sidebar-brand strong {
            display: block;
            font-size: 1rem;
            line-height: 1.2;
        }

        .sidebar-brand span {
            font-size: 0.75rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Bouton « Retour au site » : bien visible, distinct de la navigation */
        .admin-sidebar a.back-to-site {
            margin: 0.25rem 1rem 1rem;
            padding: 0.7rem 0.9rem;
            border-radius: 8px;
            background: var(--admin-brand);
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            justify-content: space-between;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            transition: background 0.2s, transform 0.2s;
        }

        .admin-sidebar a.back-to-site:hover {
            background: var(--admin-brand-dark);
            transform: translateY(-1px);
        }

        .admin-sidebar a.back-to-site .back-label {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .admin-sidebar a.back-to-site .bx-right-arrow-alt {
            opacity: 0.7;
            transition: transform 0.2s;
        }

        .admin-sidebar a.back-to-site:hover .bx-right-arrow-alt {
            transform: translateX(3px);
        }

        .sidebar-section {
            padding: 1rem 1.5rem 0.4rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .admin-sidebar a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.5rem;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.92rem;
            transition: background 0.2s, color 0.2s;
            border-left: 3px solid transparent;
        }

        .admin-sidebar nav a:hover {
            background: var(--admin-sidebar-hover);
            color: white;
        }

        .admin-sidebar nav a.active {
            background: var(--admin-sidebar-hover);
            color: white;
            border-left-color: #5eb3c0;
            font-weight: 500;
        }

        .admin-sidebar a i {
            font-size: 1.2rem;
        }

        .sidebar-count {
            margin-left: auto;
            background: var(--admin-danger);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--admin-brand);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .sidebar-user {
            flex: 1;
            min-width: 0;
            font-size: 0.85rem;
        }

        .sidebar-user span {
            display: block;
            font-size: 0.72rem;
            color: #94a3b8;
        }

        .sidebar-logout {
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 6px;
        }

        .sidebar-logout:hover {
            color: white;
            background: var(--admin-sidebar-hover);
        }

        /* --- MAIN CONTENT --- */
        .admin-content {
            margin-left: 250px;
            padding: 2rem 2.5rem;
            flex: 1;
            min-width: 0;
            /* Évite que les éléments enfants larges cassent le flex */
            transition: padding 0.3s ease;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.75rem;
            gap: 1rem;
            flex-wrap: wrap;
            /* Aligne les boutons sous le titre si l'écran est trop petit */
        }

        .admin-header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--admin-text);
            margin: 0;
        }

        .admin-subtitle {
            color: var(--admin-muted);
            font-size: 0.92rem;
            margin-top: 0.25rem;
        }

        /* --- ALERTES --- */
        .admin-alert {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            border-radius: var(--admin-radius);
            margin-bottom: 1.25rem;
            font-size: 0.92rem;
            border: 1px solid;
            animation: alertIn 0.25s ease-out;
        }

        .admin-alert i {
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .admin-alert ul {
            margin: 0;
            padding-left: 1.1rem;
        }

        .admin-alert .alert-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 1.2rem;
            line-height: 1;
            cursor: pointer;
            color: inherit;
            opacity: 0.6;
        }

        .admin-alert .alert-close:hover {
            opacity: 1;
        }

        .admin-alert.is-success {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }

        .admin-alert.is-error {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .admin-alert.is-warning {
            background: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }

        .admin-alert.is-warning a {
            color: inherit;
            font-weight: 600;
        }

        @keyframes alertIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- BUTTONS & ELEMENTS --- */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid transparent;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            transition: background 0.15s, box-shadow 0.15s, transform 0.15s;
        }

        .btn:active {
            transform: translateY(1px);
        }

        .btn:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.35);
        }

        .btn-primary {
            background: var(--admin-primary);
            border-color: var(--admin-primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--admin-primary-dark);
            border-color: var(--admin-primary-dark);
            color: white;
        }

        .btn-danger {
            background: var(--admin-danger);
            border-color: var(--admin-danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            border-color: #dc2626;
            color: white;
        }

        .btn-secondary {
            background: white;
            border-color: var(--admin-border);
            color: #334155;
        }

        .btn-secondary:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: var(--admin-text);
        }

        /* Conteneur pour rendre les tableaux scrollables horizontalement sur mobile */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border);
            border-radius: var(--admin-radius);
            background: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            white-space: nowrap;
            /* Empêche le texte des cellules de revenir à la ligne brutalement */
        }

        th {
            background: #f8fafc;
            padding: 0.85rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--admin-muted);
            border-bottom: 1px solid var(--admin-border);
        }

        td {
            padding: 0.85rem 1rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        tr:hover td {
            background: #f8fafc;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.4rem;
        }

        .form-group input:not([type=checkbox]):not([type=radio]),
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.6rem 0.85rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.9rem;
            font-family: inherit;
            background: white;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .form-group input[type=checkbox] {
            width: 18px;
            height: 18px;
            accent-color: var(--admin-primary);
            cursor: pointer;
        }

        .form-check-inline {
            display: flex !important;
            align-items: center;
            gap: 0.6rem;
            cursor: pointer;
        }

        .form-hint {
            font-size: 0.78rem;
            color: var(--admin-muted);
            margin-top: 0.35rem;
        }

        .card {
            background: white;
            border-radius: var(--admin-radius);
            padding: 1.5rem;
            border: 1px solid var(--admin-border);
            box-shadow: var(--admin-shadow);
        }

        /* Pastilles (accès, statut…) */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
        }

        .pill.is-brand {
            background: var(--admin-brand-soft);
            color: var(--admin-brand);
        }

        .pill.is-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .pill.is-purple {
            background: #ede9fe;
            color: #6d28d9;
        }

        .pill.is-amber {
            background: #fef3c7;
            color: #b45309;
        }

        /* Barre de recherche des listes */
        .admin-toolbar {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 1rem;
        }

        .admin-search {
            position: relative;
            flex: 1;
            min-width: 220px;
            max-width: 420px;
        }

        .admin-search i {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .admin-search input {
            width: 100%;
            padding: 0.6rem 0.85rem 0.6rem 2.4rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.9rem;
            background: white;
        }

        .admin-search input:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .admin-search-empty {
            display: none;
            text-align: center;
            color: var(--admin-muted);
            padding: 2rem 1rem;
        }

        /* --- TOM SELECT (listes avec recherche) aux couleurs du backoffice --- */
        .ts-wrapper.single .ts-control,
        .ts-wrapper.multi .ts-control {
            padding: 0.6rem 0.85rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.9rem;
            box-shadow: none;
            background: white;
        }

        .ts-wrapper.focus .ts-control {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .ts-dropdown {
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
            margin-top: 4px;
            font-size: 0.9rem;
            overflow: hidden;
        }

        .ts-dropdown .option {
            padding: 0.55rem 0.85rem;
        }

        .ts-dropdown .active {
            background: var(--admin-brand-soft);
            color: var(--admin-text);
        }

        .ts-dropdown .optgroup-header {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--admin-muted);
            background: #f8fafc;
        }

        .ts-dropdown .no-results {
            padding: 0.6rem 0.85rem;
            color: var(--admin-muted);
        }

        .ts-dropdown .highlight {
            background: #fde68a;
            border-radius: 2px;
        }

        .home:hover {
            background: var(--admin-sidebar);
        }

        /* --- OVERLAY MOBILE --- */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.5);
            z-index: 998;
        }

        /* -------------------------------------------
           MEDIA QUERIES (Écrans < 768px)
        ------------------------------------------- */
        @media (max-width: 768px) {
            .admin-topbar {
                display: flex;
            }

            .admin-sidebar {
                transform: translateX(-100%);
                /* Masquée par défaut sur mobile */
            }

            .admin-sidebar.open {
                transform: translateX(0);
                /* Affichée quand on clique sur le burger */
            }

            .admin-sidebar.open+.sidebar-overlay {
                display: block;
            }

            .admin-content {
                margin-left: 0;
                padding: calc(60px + 1.25rem) 1rem 1.5rem;
                /* Plus d'espace en haut pour la barre mobile */
            }

            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .admin-header h1 {
                font-size: 1.35rem;
            }

            .card {
                padding: 1.1rem;
            }
        }
    </style>
    @stack('styles')
</head>

@php
    $adminUser = Auth::user();
    $initiales = strtoupper(mb_substr($adminUser->firstname ?? '', 0, 1) . mb_substr($adminUser->name ?? '', 0, 1));
    $emailsNonLus = \App\Models\ContactMessage::where('is_read', false)->count();
    $maintenance = \App\Http\Controllers\Admin\AdminDashboardController::echeance();
@endphp

<body>

    {{-- Barre du haut (mobile uniquement) --}}
    <header class="admin-topbar">
        <button class="mobile-toggle" id="menuToggle" aria-label="Ouvrir le menu">
            <i class='bx bx-menu'></i>
        </button>
        <span class="topbar-title">@yield('title')</span>
        <a href="{{ route('home') }}" class="topbar-site" title="Retour au site" aria-label="Retour au site">
            <i class='bx bx-home-alt'></i>
        </a>
    </header>

    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('img/favicon.png') }}" alt="">
            <div>
                <strong>Coach CRPE</strong>
                <span>Administration</span>
            </div>
        </div>

        <a class="back-to-site" href="{{ route('home') }}">
            <span class="back-label"><i class='bx bx-globe'></i> Retour au site</span>
            <i class='bx bx-right-arrow-alt'></i>
        </a>

        <nav>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class='bx bx-grid-alt'></i> Tableau de bord
            </a>

            <div class="sidebar-section">Élèves</div>
            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class='bx bx-group'></i> Utilisateurs
            </a>
            <a href="{{ route('admin.devoirs.index') }}"
                class="{{ request()->routeIs('admin.devoirs*') ? 'active' : '' }}">
                <i class='bx bxs-book-content'></i> Rendus de devoirs
            </a>
            <a href="{{ route('admin.evenements') }}" class="{{ request()->routeIs('admin.evenements*') ? 'active' : '' }}">
                <i class='bx bx-calendar'></i> Événements
            </a>

            <div class="sidebar-section">Formation</div>
            <a href="{{ route('admin.blocs') }}" class="{{ request()->routeIs('admin.blocs*') ? 'active' : '' }}">
                <i class='bx bx-category'></i> Blocs
            </a>
            <a href="{{ route('admin.formations') }}" class="{{ request()->routeIs('admin.formations*') ? 'active' : '' }}">
                <i class='bx bx-book'></i> Modules
            </a>
            <a href="{{ route('admin.cours') }}" class="{{ request()->routeIs('admin.cours*', 'admin.sections*') ? 'active' : '' }}">
                <i class='bx bx-file'></i> Cours
            </a>

            <div class="sidebar-section">Site</div>
            <a href="{{ route('admin.messages.index') }}"
                class="{{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
                <i class='bx bx-envelope'></i> Gestion des emails
                @if($emailsNonLus)
                    <span class="sidebar-count">{{ $emailsNonLus > 99 ? '99+' : $emailsNonLus }}</span>
                @endif
            </a>
            <a href="{{ route('admin.contenu') }}" class="{{ request()->routeIs('admin.contenu*') ? 'active' : '' }}">
                <i class='bx bx-edit-alt'></i> Contenu du site
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-avatar">{{ $initiales }}</div>
            <div class="sidebar-user">
                {{ $adminUser->firstname }} {{ $adminUser->name }}
                <span>Tuteur</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-logout" title="Se déconnecter" aria-label="Se déconnecter">
                    <i class='bx bx-log-out'></i>
                </button>
            </form>
        </div>
    </aside>

    <div class="sidebar-overlay" id="overlay"></div>

    <main class="admin-content">
        {{-- Rappel de paiement de la maintenance (5 derniers jours du mois, hors tableau de bord qui l'affiche déjà) --}}
        @if($maintenance['rappel'] && !request()->routeIs('admin.dashboard'))
            <div class="admin-alert is-warning" data-maintenance-alert="{{ $maintenance['echeance']->format('Y-m') }}">
                <i class='bx bx-credit-card'></i>
                <div>
                    Maintenance <strong>Pack Essentiel</strong> : <strong>49 €</strong> à régler
                    {{ $maintenance['joursRestants'] === 0 ? "aujourd'hui" : 'le ' . $maintenance['echeance']->locale('fr')->translatedFormat('j F') }}.
                    <a href="{{ route('admin.dashboard') }}#maintenance">Voir le détail</a>
                </div>
                <button type="button" class="alert-close" aria-label="Masquer">&times;</button>
            </div>
        @endif

        @if(session('success'))
            <div class="admin-alert is-success">
                <i class='bx bx-check-circle'></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="alert-close" aria-label="Fermer">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="admin-alert is-error">
                <i class='bx bx-error-circle'></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="alert-close" aria-label="Fermer">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="admin-alert is-error">
                <i class='bx bx-error-circle'></i>
                <div>
                    @if($errors->count() === 1)
                        {{ $errors->first() }}
                    @else
                        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    @endif
                </div>
                <button type="button" class="alert-close" aria-label="Fermer">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            function toggleMenu() {
                sidebar.classList.toggle('open');
            }

            toggleBtn.addEventListener('click', toggleMenu);
            overlay.addEventListener('click', toggleMenu);

            // Fermeture des alertes (le rappel de maintenance reste masqué jusqu'au mois suivant)
            document.querySelectorAll('.admin-alert').forEach((alert) => {
                const key = alert.dataset.maintenanceAlert ? `maintenance-masquee-${alert.dataset.maintenanceAlert}` : null;
                try {
                    if (key && localStorage.getItem(key)) alert.remove();
                } catch (e) { }

                alert.querySelector('.alert-close')?.addEventListener('click', () => {
                    try {
                        if (key) localStorage.setItem(key, '1');
                    } catch (e) { }
                    alert.remove();
                });
            });

            // Listes déroulantes avec recherche (taper pour filtrer).
            // Ajouter data-native sur un <select> pour garder la liste classique.
            if (window.TomSelect) {
                document.querySelectorAll('.admin-content select:not([data-native]):not([multiple])').forEach((select) => {
                    new TomSelect(select, {
                        create: false,
                        allowEmptyOption: true,
                        maxOptions: null,
                        searchField: ['text'],
                        placeholder: 'Rechercher…',
                        render: {
                            no_results: () => '<div class="no-results">Aucun résultat</div>',
                        },
                    });
                });
            }

            // Recherche instantanée dans les tableaux : <input data-table-search="#idTableau">
            document.querySelectorAll('[data-table-search]').forEach((input) => {
                const table = document.querySelector(input.dataset.tableSearch);
                if (!table) return;
                const rows = table.querySelectorAll('tbody tr');
                const empty = document.querySelector(input.dataset.tableEmpty || null);
                // Minuscules et sans accents (on retire les diacritiques U+0300 à U+036F après décomposition)
                const normaliser = (s) => [...s.normalize('NFD')]
                    .filter((c) => c.charCodeAt(0) < 0x300 || c.charCodeAt(0) > 0x36f).join('').toLowerCase();

                const filtrer = () => {
                    const termes = normaliser(input.value.trim()).split(/\s+/).filter(Boolean);
                    const filtre = document.querySelector(input.dataset.tableFilter || null)?.value || '';
                    let visibles = 0;

                    rows.forEach((row) => {
                        const texte = normaliser(row.textContent);
                        const ok = termes.every((t) => texte.includes(t)) && (!filtre || row.dataset.filter === filtre);
                        row.style.display = ok ? '' : 'none';
                        if (ok) visibles++;
                    });

                    if (empty) empty.style.display = visibles ? 'none' : 'block';
                };

                input.addEventListener('input', filtrer);
                document.querySelector(input.dataset.tableFilter || null)?.addEventListener('change', filtrer);
            });
        });
    </script>

    @stack('scripts')

    @include('components.chat-config')

    @auth
        @include('components.chat')

        @if(Auth::user()->is_tutor)
            <div class="tutor-channels-btn" id="open-channels-btn"
                style="position: fixed; bottom: 20px; right: 20px; background: #2c6e7a; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; cursor: pointer; z-index: 999; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                <i class='bx bxs-chat'></i>
                <span id="channels-notif-badge" class="notif-badge" style="display:none;">0</span>
            </div>
            <div id="channels-popup" class="chat-popup"
                style="display: none; position: fixed; bottom: 90px; right: 20px; width: 350px; height: 450px; background: white; border-radius: 12px; box-shadow: 0 5px 25px rgba(0,0,0,0.15); z-index: 1000; flex-direction: column; overflow: hidden;">
                <div class="chat-header"
                    style="background: #2c6e7a; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                    <h4 style="margin: 0; font-size: 1rem;"><i class='bx bxs-user-detail'></i> Mes canaux élèves</h4>
                    <button id="close-channels-btn"
                        style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer;">&times;</button>
                </div>
                <div class="channels-body" id="channels-list-container" style="flex: 1; overflow-y: auto; padding: 0.5rem;">
                    <p style="text-align: center; color: gray; margin-top: 2rem;">Chargement des conversations...</p>
                </div>
            </div>
        @else
            <div class="tutor-chat-btn">
                <i class='dashboard-toggle-btn-i bx bxs-message-rounded-dots'></i>
                <span id="chat-notif-badge" class="notif-badge" style="display:none;">0</span>
            </div>
        @endif
    @endauth
</body>

</html>