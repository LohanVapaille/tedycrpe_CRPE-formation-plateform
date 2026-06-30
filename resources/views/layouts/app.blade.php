<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Objectif CRPE') - TedyCRPE</title>
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
                <h3>Tedy Florette Formation CRPE</h3>
                <p>SAS au capital de 180 380€<br>
                    18, rue Lucien Granet<br>
                    33150, Cenon, France</p>
                <p class="footer-phone">Tél : <a href="tel:+33556401402">+33 5 56 40 14 02</a></p>
            </div>

            <!-- Colonne Centrale : Liens de navigation -->
            <div class="footer-column footer-links">
                <a href="#">Plan du site</a>
                <a href="#">A propos</a>
                <a href="#">Mentions légales</a>
                <a href="#">Données personnelles</a>
                <a href="#">Conditions générales de vente (CGV)</a>
                <a href="#">Newsletter</a>
                <a href="#">Glossaire</a>
                <a href="#">Contact</a>
                <a href="{{ route('admin.users') }}">Admin</a>
            </div>

            <!-- Colonne Droite : Réseaux Sociaux -->
            <div class="footer-column footer-socials">
                <a href="#" target="_blank" class="social-link">
                    <i class="fab fa-facebook-square"></i> Facebook
                </a>
                <a href="#" target="_blank" class="social-link">
                    <i class="fab fa-linkedin"></i> LinkedIn
                </a>
            </div>

        </div>

        <!-- Ligne de séparation et crédit -->
        <div class="footer-bottom">
            <hr class="footer-divider">
            <p class="footer-credits">Site développé par <a href="#" target="_blank">Lohan Vapaille</a></p>
        </div>
    </footer>


    @auth
        <script>
            window.chatConfig = {
                currentUserId: {{ Auth::id() }},
                isTuteur: {{ Auth::user()->is_tutor ? 'true' : 'false' }},
                initialChatId: {{ !Auth::user()->is_tutor ? ($chat->id ?? 'null') : 'null' }},
                baseUrl: "{{ url('') }}",
                csrfToken: "{{ csrf_token() }}"
            };
        </script>

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