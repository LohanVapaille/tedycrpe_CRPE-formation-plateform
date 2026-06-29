<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Objectif CRPE') - TedyCRPE</title>
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



</body>

</html>