@extends('layouts.gabaritsansfooter')

@section('title', 'Mon Tableau de bord')

@section('content')

    <div class="dashboard-layout fade-in">

        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <button class="dashboard-toggle-btn dashboard-toggle-btn--tdb" id="sidebarToggle" aria-label="Ouvrir le menu">
            <i class='bx bx-menu-alt-left'></i>
        </button>
        <aside class="dashboard-sidebar" id="dashboardSidebar">

            <div class="user-profile">
                <div class="avatar-placeholder">👤</div>
                <h3 class="user-fullname">{{ Auth::user()->firstname }} {{ Auth::user()->name }}</h3>
            </div>

            <nav class="sidebar-menu">
                <a href="#tb" class="menu-item active"><i class='bx bxs-dashboard'></i> Tableau de bord</a>
                <a href="{{ route('formation.cours', ['id' => $premierCoursId]) }}" class="menu-item"><i
                        class='bx bx-book-open'></i> Les
                    cours</a>
                <a href="{{ route('agenda.index') }}" class="menu-item"><i class='bx bx-calendar-alt'></i> Mon agenda</a>

                <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 1rem 0;">
                <a href="{{ route('logout') }}" class="menu-item logout-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    style="color: #ff8b8b;">
                    <i class='bx bx-door-open'></i> Se déconnecter
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </nav>

            {{-- On affiche l'encadré "Mon Tuteur" uniquement si l'utilisateur connecté est un élève --}}
            @if(!Auth::user()->is_tutor)
                <div class="tutor-section">
                    <h4 class="sidebar-subtitle">Mon tuteur</h4>
                    <div class="tutor-card">
                        <div class="tutor-avatar">👨‍🏫</div>
                        <div class="tutor-info">
                            <p class="tutor-name">Tedy Florette</p>
                            <p class="tutor-role">Formateur CRPE</p>
                        </div>
                    </div>
                </div>
            @endif
        </aside>

        <main class="dashboard-main">

            <section class="welcome-banner">
                <div class="banner-left">
                    <h2>Bienvenue sur votre espace, {{ Auth::user()->firstname }}</h2>
                    <p class="meta-info">Inscrit à la formation depuis
                        {{ Auth::user()->created_at?->locale('fr')->translatedFormat('F Y') }}</p>
                    <p class="meta-objective">Objectif : {{ Auth::user()->access }}</p>

                    <div class="banner-actions">
                        {{-- Groupe WhatsApp selon le module de l'élève (les deux si accès complet) --}}
                        @php
                            $groupesWhatsapp = [
                                'CRPE BAC + 3' => 'https://chat.whatsapp.com/IpBxR4aKDPTJ56Mq6BRfsm',
                                'CRPE BAC + 5' => 'https://chat.whatsapp.com/KtcIEHIbFozB99vgGvkqk0',
                            ];
                            $accesUser = Auth::user()->module();
                        @endphp
                        {{-- Élèves "E-learning" : pas de groupe WhatsApp --}}
                        @foreach(Auth::user()->isElearning() ? [] : $groupesWhatsapp as $module => $lien)
                            @if($accesUser === 'all' || $accesUser === $module)
                                <a href="{{ $lien }}" target="_blank" rel="noopener" style="background-color: green;"
                                    class=" btn-secondary-light "><i class='bx bxl-whatsapp'></i>
                                    Groupe WhatsApp{{ $accesUser === 'all' ? ' ' . str_replace('CRPE BAC + ', 'Bac+', $module) : '' }}</a>
                            @endif
                        @endforeach

                    </div>
                </div>
                <div class="banner-right">
                    <span class="progress-label">Progression globale : {{ $progressionGlobale }}&nbsp;%</span>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: {{ $progressionGlobale }}%"></div>
                    </div>
                </div>
            </section>

            <h3 id="tb" class="block-title">Tableau de bord</h3>
            <section class="stats-grid {{ $afficherDevoirs ? '' : 'stats-grid--single' }}">
                <div class="stat-box">
                    <span class="stat-title">Avancement des chapitres</span>
                    <span class="stat-value">{{ $totalCoursTermines }} <span class="stat-separator">/</span>
                        {{ $totalCoursFormation }}</span>
                </div>
                @if($afficherDevoirs)
                    <div class="stat-box">
                        <span class="stat-title">Fichiers & Devoirs</span>
                        <span class="stat-value">{{ $nombreFichiersEnvoyes }}
                            {{ $nombreFichiersEnvoyes > 1 ? 'fichiers envoyés' : 'fichier envoyé' }}</span>
                    </div>
                @endif
            </section>

            <h3 class="block-title">Le flux d'activité</h3>
            <section class="resume-activity-box">
                @if($premiereVisite)
                    <p>Bienvenue ! Commencez votre formation dès maintenant.</p>
                    <a href="{{ route('formation.cours', ['id' => $premierCoursId]) }}" class="btn-dark-blue">
                        Commencer ma formation
                    </a>
                @else
                    <p>Reprenez là où vous vous étiez arrêté !</p>
                    <a href="{{ route('formation.cours', ['id' => $premierCoursId]) }}" class="btn-dark-blue">
                        Accéder à ma formation
                    </a>
                @endif
            </section>

            <div class="matieres-section-header">
                <p class="filter-label">Accéder aux cours disponibles :</p>
            </div>

            <section id="cours" class="matieres-secure-grid">
                @if(Auth::user()->module() === 'all')
                    {{-- L'utilisateur a accès à tout : on sépare par module --}}
                    @php
                        // On groupe les matières par module_associe
                        $groupes = $matieres->groupBy('module_associe');
                    @endphp
                    @forelse($groupes as $moduleName => $matieresDuGroupe)
                        <div class="module-group-container" style="grid-column: 1 / -1; margin-top: 1.5rem; margin-bottom: 1rem;">
                            <h3 class="module-title"
                                style="font-size: 1.4rem; color: #2c6e7a; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; text-transform: uppercase;">
                                {{ $moduleName === 'CRPE BAC + 3' ? 'Module BAC + 3' : 'Module CRPE BAC + 5' }}
                            </h3>
                        </div>

                        @foreach($matieresDuGroupe as $index => $matiere)
                            <div class="secure-matiere-card">
                                <span class="matiere-number">{{ $matiere->name }}</span>
                                @if($matiere->cours->isNotEmpty())
                                    <a href="{{ route('formation.cours', ['id' => $matiere->cours->first()->id]) }}"
                                        class="btn-action-course">
                                        Lire le cours
                                    </a>
                                @else
                                    <span class="btn-action-course" style="opacity:0.5; cursor:not-allowed;">Bientôt disponible</span>
                                @endif
                                <span class="matiere-progression">{{ $matiere->progression ?? 0 }}&nbsp;% de progression</span>
                            </div>
                        @endforeach
                    @empty
                        <p class="no-data">Aucune matière n'est disponible pour le moment.</p>
                    @endforelse
                @endif
                @if(Auth::user()->module() === 'CRPE BAC + 3')
                    {{-- L'utilisateur a accès uniquement au module BAC + 3 --}}
                    @forelse($matieres as $index => $matiere)
                        <div class="secure-matiere-card">
                            <span class="matiere-number">{{ $matiere->name }}</span>
                            @if($matiere->cours->isNotEmpty())
                                <a href="{{ route('formation.cours', ['id' => $matiere->cours->first()->id]) }}"
                                    class="btn-action-course">
                                    Lire le cours
                                </a>
                            @else
                                <span class="btn-action-course" style="opacity:0.5; cursor:not-allowed;">Bientôt disponible</span>
                            @endif
                            <span class="matiere-progression">{{ $matiere->progression ?? 0 }}&nbsp;% de progression</span>
                        </div>
                    @empty
                        <p class="no-data">Aucune matière n'est disponible pour le moment.</p>
                    @endforelse
                @endif
                @if(Auth::user()->module() === 'CRPE BAC + 5')
                    {{-- L'utilisateur a accès uniquement au module CRPE BAC + 5 --}}
                    @forelse($matieres as $index => $matiere)
                        <div class="secure-matiere-card">
                            <span class="matiere-number">{{ $matiere->name }}</span>
                            @if($matiere->cours->isNotEmpty())
                                <a href="{{ route('formation.cours', ['id' => $matiere->cours->first()->id]) }}"
                                    class="btn-action-course">
                                    Lire le cours
                                </a>
                            @else
                                <span class="btn-action-course" style="opacity:0.5; cursor:not-allowed;">Bientôt disponible</span>
                            @endif
                            <span class="matiere-progression">{{ $matiere->progression ?? 0 }}&nbsp;% de progression</span>
                        </div>
                    @empty
                        <p class="no-data">Aucune matière n'est disponible pour le moment.</p>
                    @endforelse
                @endif

            </section>

        </main>
    </div>



    {{-- Script de gestion de la sidebar responsive --}}
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const dashboardSidebar = document.getElementById('dashboardSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle?.addEventListener('click', () => {
            dashboardSidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('active');
            document.body.classList.toggle('no-scroll');
            const isOpen = dashboardSidebar.classList.contains('open');
            // Bouton collé au bord du menu ouvert, sans sortir de l'écran
            sidebarToggle.style.left = isOpen
                ? (dashboardSidebar.offsetWidth - 56) + 'px' // croix dans le coin haut droit du menu
                : '0';
            sidebarToggle.classList.toggle('is-open', isOpen);

            const icon = sidebarToggle.querySelector('i');
            if (isOpen) {
                icon.classList.remove('bx-menu-alt-left');
                icon.classList.add('bx-x');
            } else {
                icon.classList.remove('bx-x');
                icon.classList.add('bx-menu-alt-left');
            }
        });

        sidebarOverlay?.addEventListener('click', () => {
            dashboardSidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
            document.body.classList.remove('no-scroll');
            sidebarToggle.style.left = '0';
            sidebarToggle.classList.remove('is-open');

            const icon = sidebarToggle.querySelector('i');
            icon.classList.remove('bx-x');
            icon.classList.add('bx-menu-alt-left');
        });
    </script>


@endsection