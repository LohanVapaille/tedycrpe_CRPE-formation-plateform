<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Objectif CRPE') - TedyCRPE</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body class="site-layout">

    <header class="main-header">
        <div class="header-container">
            <nav class="main-nav">
                <a href="{{ route('home') }}" class="nav-link active">Accueil</a>
                <a href="{{ route('crpe') }}" class="nav-link">Formation CRPE</a>
                <a href="#" class="nav-link">Agenda</a>
                <a href="#" class="nav-link">Qui suis-je ?</a>
            </nav>

            <div class="user-actions">
                <a href="{{ route('formation.index') }}" class="account-btn">Mon compte</a>
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

</body>

</html>