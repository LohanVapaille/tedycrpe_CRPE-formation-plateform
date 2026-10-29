<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <title>@yield('title', 'Objectif CRPE') - TedyCRPE</title>
    <meta name="description"
        content="@yield('meta_description', 'Préparez le CRPE avec Tedy Florette grâce à des cours, des ressources et un accompagnement personnalisé.')">

    <meta name="robots" content="index, follow">

    <link rel="canonical" href="{{ url()->current() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('meta_description')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body class="site-layout">

    <header class="main-header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="header-logo">TedyCRPE</a>

            <!-- Le bouton burger pour le mobile -->
            <button class="menu-toggle" id="menuToggle">
                <i class='bx bx-menu'></i>
            </button>

            <!-- Le wrapper qui englobe tes deux blocs -->
            <div class="nav-wrapper" id="navWrapper">
                <nav class="main-nav">

                    <a href="{{ route('crpe') }}" class="nav-link">Formation CRPE</a>
                    <a href="{{ route('agenda.index') }}" class="nav-link">Agenda</a>
                    <a href="{{ route('qui-suis-je') }}" class="nav-link">Qui suis-je ?</a>
                </nav>

                <div class="user-actions">
                    <a href="{{ route('formation.index') }}" class="account-btn">Mon compte</a>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        @yield('content')

    </main>

    <footer class="main-footer">
        <div class="footer-container">

            <!-- Colonne Gauche : Infos Entreprise -->
            <div class="footer-column footer-brand">
                <h3>{{ $footerSettings['footer_company_name'] ?? 'Tedy Florette Formation CRPE' }}</h3>
                <p>{!! nl2br(e($footerSettings['footer_address'] ?? "SAS au capital de 180 380€\n18, rue Lucien Granet\n33150, Cenon, France")) !!}
                </p>
                <p class="footer-phone">Tél :
                    <a href="tel:{{ str_replace(' ', '', $footerSettings['footer_phone'] ?? '+33556401402') }}">
                        {{ $footerSettings['footer_phone'] ?? '+33 5 56 40 14 02' }}
                    </a>
                </p>
            </div>

            <!-- Colonne Centrale : Liens de navigation -->
            <div class="footer-column footer-links">
                <a href="{{ route('plan-du-site') }}">Plan du site</a>
                <a href="{{ route('qui-suis-je') }}">A propos</a>
                <a href="{{ route('mentions-legales') }}">Mentions légales</a>
                <a href="{{ route('donnees-personnelles') }}">Données personnelles</a>
                <a href="{{ route('home') }}#contact">Contact</a>
                @if(Auth::check() && Auth::user()->is_tutor)
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
            </div>

            <!-- Colonne Droite : Réseaux Sociaux -->
            <div class="footer-column footer-socials">
                <a href="{{ $footerSettings['footer_facebook_url'] ?? '#' }}" target="_blank" class="social-link">
                    <i class='bx bxl-facebook'></i> Facebook
                </a>
                <a href="{{ $footerSettings['footer_linkedin_url'] ?? '#' }}" target="_blank" class="social-link">
                    <i class='bx bxl-linkedin'></i> LinkedIn
                </a>
                <a href="{{ $footerSettings['footer_instagram_url'] ?? '#' }}" target="_blank" class="social-link">
                    <i class='bx bxl-instagram'></i> Instagram
                </a>
            </div>

        </div>



        <!-- Ligne de séparation et crédit -->
        <div class="footer-bottom">
            <hr class="footer-divider">
            <p class="footer-credits">Site développé par <a href="https://lohanvapaille.fr" target="_blank">Lohan
                    Vapaille</a> | <a href="https://lovaweb.lohanvapaille.fr" target="_blank">Lovaweb</a></p>
        </div>
    </footer>


    @auth
        @include('components.chat-config')

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

    @guest
        {{-- Structure du Popup d'Appel Découverte --}}
        <div id="discovery-popup-container" class="popup-closed">
            <button id="popup-trigger-circle" onclick="toggleDiscoveryPopup()">
                <i class='bx bx-phone' style="font-size: 1.5rem;"></i>
            </button>

            <div id="popup-content-box">
                <button id="popup-close-btn" onclick="toggleDiscoveryPopup()">×</button>

                <div class="popup-header">
                    <i class='bx bx-calendar-star' style="font-size: 2rem; color: #2c6e7a;"></i>
                    <h4>Besoin d'aide ?</h4>
                </div>

                <p>Réservez un <strong>appel découverte 100% gratuit</strong> pour échanger sur votre projet et poser toutes
                    vos questions !</p>

                <a href="{{ url('/visio') }}" class="btn-popup-action">
                    <i class='bx bx-video'></i> Réserver mon appel
                </a>
            </div>
        </div>

        {{-- Styles CSS intégrés --}}
        <style>
            #discovery-popup-container {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 9999;
                display: flex;
                align-items: flex-end;
                justify-content: flex-end;
            }

            /* --- STYLE BOUTON ROND (FERMÉ) --- */
            #popup-trigger-circle {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: #2c6e7a;
                /* Ajuste avec ta couleur principale */
                color: white;
                border: none;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                display: flex;
                align-items: center;
                justify-content: center;
                transition: transform 0.2s ease, background 0.2s ease;
            }

            #popup-trigger-circle:hover {
                transform: scale(1.05);
                background: #1e4d56;
            }

            /* Mobile / tablette : bouton plus petit, collé au coin, et marge en bas de page
               pour qu'il ne recouvre pas la fin du contenu */
            @media (max-width: 1023px) {
                #discovery-popup-container {
                    bottom: 12px;
                    right: 12px;
                }

                #popup-trigger-circle {
                    width: 44px;
                    height: 44px;
                    opacity: 0.9;
                }

                #popup-trigger-circle i {
                    font-size: 1.2rem !important;
                }
            }

            /* --- STYLE DE LA BOÎTE DE DIALOGUE (OUVERTE) --- */
            #popup-content-box {
                display: none;
                width: 300px;
                background: white;
                border-radius: 12px;
                padding: 1.5rem;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
                border: 1px solid #e2e8f0;
                position: relative;
                animation: popupFadeIn 0.3s ease-out;
            }

            #popup-close-btn {
                position: absolute;
                top: 10px;
                right: 12px;
                background: none;
                border: none;
                font-size: 1.5rem;
                color: #94a3b8;
                cursor: pointer;
                line-height: 1;
            }

            #popup-close-btn:hover {
                color: #334155;
            }

            .popup-header {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 0.75rem;
            }

            .popup-header h4 {
                margin: 0;
                font-size: 1.1rem;
                color: #1e293b;
                font-weight: bold;
            }

            #popup-content-box p {
                font-size: 0.9rem;
                color: #475569;
                margin: 0 0 1.25rem 0;
                line-height: 1.4;
            }

            .btn-popup-action {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                background: #2c6e7a;
                color: white;
                text-decoration: none;
                padding: 10px;
                border-radius: 6px;
                font-size: 0.9rem;
                font-weight: bold;
                text-align: center;
                transition: background 0.2s;
            }

            .btn-popup-action:hover {
                background: #1e4d56;
            }

            /* --- GESTION DES ÉTATS (OUVERT / FERMÉ) --- */
            #discovery-popup-container.popup-open #popup-trigger-circle {
                display: none;
            }

            #discovery-popup-container.popup-open #popup-content-box {
                display: block;
            }

            @keyframes popupFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(15px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>

        {{-- Script JavaScript & LocalStorage --}}
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const container = document.getElementById('discovery-popup-container');

                // Étape 1 : Récupérer l'état sauvegardé dans le localStorage
                // Si aucune donnée n'existe, on choisit l'état "opened" par défaut (ou "closed" selon ta préférence)
                const popupState = localStorage.getItem('discovery_popup_state') || 'opened';

                // Étape 2 : Appliquer l'état au chargement de la page
                if (popupState === 'opened') {
                    container.classList.remove('popup-closed');
                    container.classList.add('popup-open');
                } else {
                    container.classList.remove('popup-open');
                    container.classList.add('popup-closed');
                }
            });

            // Étape 3 : Fonction de bascule au clic
            function toggleDiscoveryPopup() {
                const container = document.getElementById('discovery-popup-container');

                if (container.classList.contains('popup-open')) {
                    // Fermeture
                    container.classList.remove('popup-open');
                    container.classList.add('popup-closed');
                    localStorage.setItem('discovery_popup_state', 'closed');
                } else {
                    // Ouverture
                    container.classList.remove('popup-closed');
                    container.classList.add('popup-open');
                    localStorage.setItem('discovery_popup_state', 'opened');
                }
            }
        </script>
    @endguest

</body>

</html>