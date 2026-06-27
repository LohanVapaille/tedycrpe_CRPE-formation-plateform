<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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



</body>

</html>