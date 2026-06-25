<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Objectif CRPE') - TedyCRPE</title>

    @vite(['resources/css/app.scss'])
</head>

<body class="site-layout">

    <header class="main-header">
        <div class="header-container">
            <nav class="main-nav">
                <a href="{{ route('home') }}" class="nav-link active">Accueil</a>
                <a href="#" class="nav-link">Formation CRPE</a>
                <a href="#" class="nav-link">Qui suis-je ?</a>
            </nav>

            <div class="user-actions">
                <a href="{{ route('login') }}" class="account-btn">Mon compte</a>
            </div>
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="main-footer">
        <p>&copy; {{ date('Y') }} TedyCRPE - Tous droits réservés.</p>
    </footer>

</body>

</html>