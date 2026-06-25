@extends('layouts.app')

@section('title', 'Mon Tableau de bord')

@section('content')
    <div class="dashboard-layout fade-in">

        <!-- 1. SIDEBAR (Colonne Gauche) -->
        <aside class="dashboard-sidebar">
            <div class="user-profile">
                <div class="avatar-placeholder">
                    <!-- Icône utilisateur simplifiée ou image -->
                    👤
                </div>
                <h3 class="user-fullname">{{ Auth::user()->firstname }} {{ Auth::user()->name }}</h3>
            </div>

            <nav class="sidebar-menu">
                <a href="#" class="menu-item active">📊 Tableau de bord</a>
                <a href="#" class="menu-item">📚 Les cours</a>
                <a href="#" class="menu-item">📹 Modules vidéos</a>
            </nav>

            <div class="tutor-section">
                <h4 class="sidebar-subtitle">Mon tuteur</h4>
                <div class="tutor-card">
                    <!-- Tu pourras lier cette image dynamiquement plus tard -->
                    <div class="tutor-avatar">👨‍🏫</div>
                    <div class="tutor-info">
                        <p class="tutor-name">Tedy Floretto</p>
                        <p class="tutor-role">Formateur CRPE</p>
                    </div>
                    <!-- Bulle de chat reliée au modèle Chat/Message plus tard -->
                    <a href="#" class="tutor-chat-btn">💬</a>
                </div>
            </div>
        </aside>

        <!-- 2. CONTENU PRINCIPAL (Zone Droite) -->
        <main class="dashboard-main">

            <!-- Section Bienvenue & Progression -->
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

            <!-- Statistiques rapides -->
            <h3 class="block-title">Tableau de bord</h3>
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

            <!-- Bouton de reprise d'activité rapide -->
            <h3 class="block-title">Le Flux d'Activité</h3>
            <section class="resume-activity-box">
                <p>Reprenez là où vous vous étiez arrêté !</p>
                <a href="#" class="btn-dark-blue">Accéder à ma formation</a>
            </section>

            <!-- Filtres et liste des matières dynamiques -->
            <div class="matieres-section-header">
                <p class="filter-label">Accéder au cours disponible :</p>
                <div class="filter-badges">
                    <span class="badge active">✓ Tous</span>
                    <span class="badge">Oraux</span>
                    <span class="badge">Écrit</span>
                </div>
            </div>

            <!-- Grille des matières issues de la BDD -->
            <section class="matieres-secure-grid">
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
@endsection