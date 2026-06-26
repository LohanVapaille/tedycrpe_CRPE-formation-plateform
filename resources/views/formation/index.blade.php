@extends('layouts.gabaritsansfooter')

@section('title', 'Mon Tableau de bord')

@section('content')
    <div class="dashboard-layout fade-in">

        <aside class="dashboard-sidebar">
            <div class="user-profile">
                <div class="avatar-placeholder">
                    👤
                </div>
                <h3 class="user-fullname">{{ Auth::user()->firstname }} {{ Auth::user()->name }}</h3>
            </div>

            <nav class="sidebar-menu">
                <a href="#tb" class="menu-item active">📊 Tableau de bord</a>
                <a href="#cours" class="menu-item">📚 Les cours</a>
                <a href="#" class="menu-item">📹 Modules vidéos</a>

                <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 1rem 0;">
                <a href="{{ route('logout') }}" class="menu-item logout-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    style="color: #ff8b8b;">
                    🚪 Se déconnecter
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" style="display: none;">
                    @csrf
                </form>
            </nav>

            <div class="tutor-section">
                <h4 class="sidebar-subtitle">Mon tuteur</h4>
                <div class="tutor-card">
                    <div class="tutor-avatar">👨‍🏫</div>
                    <div class="tutor-info">
                        <p class="tutor-name">Tedy Florette</p>
                        <p class="tutor-role">Formateur CRPE</p>
                    </div>
                    <!-- <a href="#" class="tutor-chat-btn"><i class='bx bxs-chat'></i></a> -->
                </div>
            </div>
        </aside>

        <main class="dashboard-main">

            <section class="welcome-banner">
                <div class="banner-left">
                    <h2>Bienvenue sur votre espace, {{ Auth::user()->firstname }}</h2>
                    <p class="meta-info">Inscrit à la formation depuis Septembre 2025</p>
                    <p class="meta-objective">Objectif : {{ $matieres->first()->module_associe ?? 'CRPE BAC + 5' }}</p>

                    <div class="banner-actions">
                        <a href="#" class="btn-secondary-light">Modifier le profil</a>
                        <a href="#" class="btn-orange">Besoin d'aide</a>
                    </div>
                </div>
                <div class="banner-right">
                    <span class="progress-label">Progression globale : 60%</span>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: 60%"></div>
                    </div>
                </div>
            </section>

            <h3 id="tb" class="block-title">Tableau de bord</h3>
            <section class="stats-grid">
                <div class="stat-box">
                    <span class="stat-title">Temps d’apprentissage</span>
                    <span class="stat-value">42 heures</span>
                </div>
                <div class="stat-box">
                    <span class="stat-title">Modules terminés</span>
                    <span class="stat-value">5 Cours</span>
                </div>
            </section>

            <h3 class="block-title">Le Flux d'Activité</h3>
            <section class="resume-activity-box">
                <p>Reprenez là où vous vous étiez arrêté !</p>
                <a href="{{ route('formation.cours', ['id' => 1]) }}" class="btn-dark-blue">Accéder à ma formation</a>
            </section>

            <div class="matieres-section-header">
                <p class="filter-label">Accéder au cours disponible :</p>
                <div class="filter-badges">
                    <span class="badge active">✓ Tous</span>
                    <span class="badge">Oraux</span>
                    <span class="badge">Écrit</span>
                </div>
            </div>

            <section id="cours" class="matieres-secure-grid">
                @forelse($matieres as $index => $matiere)
                    <div class="secure-matiere-card">
                        <span class="matiere-number">{{ $index + 1 }}. {{ $matiere->name }}</span>
                        <a href="#" class="btn-action-course">Lire le cours</a>
                        <span class="matiere-progression">0% de progression</span>
                    </div>
                @empty
                    <p class="no-data">Aucune matière n'est disponible pour le moment.</p>
                @endforelse
            </section>

        </main>
    </div>

    @include('components.chat')
@endsection