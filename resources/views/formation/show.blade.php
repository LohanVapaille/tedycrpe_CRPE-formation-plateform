@extends('layouts.gabaritsansfooter')

@section('title', $coursSelectionne->name)

@section('content')
    <div class="dashboard-layout fade-in">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <button class="dashboard-toggle-btn showone" id="sidebarToggle">
            <i class='dashboard-toggle-btn-i bx bxs-info-circle'></i>
        </button>
        <aside class="dashboard-sidebar w-100 sidebar-menu-pagecours" id="dashboardSidebar">


            <div style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <a href="{{ route('formation.index') }}" class="btn-back-dashboard"
                    style="text-decoration: none; display: inline-block;">
                    ⬅️ Tableau de bord
                </a>
            </div>

            <nav class="sidebar-menu menu-formation">
                @if(Auth::user()->access === 'all')

                    <details class="level-group" open>
                        <summary class="level-title-sidebar">🎓 CRPE Bac +5</summary>
                        <div class="level-content" style="padding-left: 0.75rem; margin-top: 0.5rem;">
                            @foreach($matieres->where('module_associe', 'CRPE BAC + 5') as $matiere)
                                @include('partials.sidebar-matiere-item', ['matiere' => $matiere])
                            @endforeach
                        </div>
                    </details>

                    <details class="level-group" style="margin-top: 1rem;" open>
                        <summary class="level-title-sidebar">🎓 CRPE Bac +3</summary>
                        <div class="level-content" style="padding-left: 0.75rem; margin-top: 0.5rem;">
                            @foreach($matieres->where('module_associe', 'CRPE BAC + 3') as $matiere)
                                @include('partials.sidebar-matiere-item', ['matiere' => $matiere])
                            @endforeach
                        </div>
                    </details>

                @else

                    @foreach($matieres as $matiere)
                        @include('partials.sidebar-matiere-item', ['matiere' => $matiere])
                    @endforeach

                @endif
            </nav>

        </aside>

        <!-- ZONE DE CONTENU CENTRAL (Le Cours) -->
        <main class="dashboard-main dynamic-course-content">

            <!-- En-tête du cours (Comme le bloc bleu "1. Mathématiques" de ta maquette) -->
            <div class="course-main-header">
                <div>
                    <h2>{{ $coursSelectionne->name }}</h2>
                    <p>Matière : {{ $coursSelectionne->matiere->name }}</p>

                    @php
                        // On récupère la matière correspondante dans la liste où les progressions sont calculées
                        $matiereActuelle = $matieres->firstWhere('id', $coursSelectionne->id_matiere);
                        $progressionMatiere = $matiereActuelle->progression ?? 0;
                    @endphp
                </div>
                <div class="progress-container-mini">
                    <span class="matiere-progression">
                        Progression de la matière : {{ $progressionMatiere }}%
                    </span>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: {{ $progressionMatiere }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Rendu dynamique des sections selon leur TYPE -->
            <div class="course-sections-wrapper">
                @foreach($coursSelectionne->sections as $section)

                    @if($section->type === 'titre')
                        <h3 class="section-render-title">{{ $section->titre }}</h3>
                    @endif

                    @if($section->type === 'txt')
                        <div class="section-render-text">
                            <p>{{ $section->contenu }}</p>
                        </div>
                    @endif

                    @if($section->type === 'video')
                        <div class="section-render-video">
                            @if($section->titre)
                            <h4>{{ $section->titre }}</h4> @endif
                            <div class="video-responsive">
                                <iframe src="{{ $section->contenu }}" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    @endif

                    @if($section->type === 'img')
                        <div class="section-render-image">
                            @if($section->titre)
                            <h4>{{ $section->titre }}</h4> @endif
                            <img src="{{ asset($section->contenu) }}" alt="{{ $section->titre ?? 'Image du cours' }}">
                        </div>
                    @endif

                    @if($section->type === 'upload')
                        <div class="section-render-upload">
                            <h4>{{ $section->titre ?? 'Rendu de devoir' }}</h4>
                            <p class="upload-instruction">{{ $section->contenu }}</p>
                            <form action="#" method="POST" enctype="multipart/form-data" class="upload-box-form">
                                @csrf
                                <input type="file" name="devoir" required>
                                <button type="submit" class="button-style btn-bleuclair">Envoyer mon fichier</button>
                            </form>
                        </div>
                    @endif

                @endforeach
            </div>

            <!-- Bouton de bas de page -->
            <div class="course-footer-actions mb-5">
                <form action="{{ route('formation.cours.terminer', $coursSelectionne->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="button-style btn-dark-blue">
                        Cours suivant et valider
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>sidebarToggle?.addEventListener('click', () => {
            dashboardSidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('active');
            document.body.classList.toggle('no-scroll');
            const isOpen = dashboardSidebar.classList.contains('open');
            sidebarToggle.style.right = isOpen ? '5px' : '0';
            sidebarToggle.style.left = isOpen ? 'auto' : '0';

            // Change l'icône
            const icon = sidebarToggle.querySelector('i');
            if (isOpen) {
                icon.classList.remove('bxs-info-circle');
                icon.classList.add('bx-x');
            } else {
                icon.classList.remove('bx-x');
                icon.classList.add('bxs-info-circle');
            }
        });

        sidebarOverlay?.addEventListener('click', () => {
            dashboardSidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
            document.body.classList.remove('no-scroll');
            sidebarToggle.style.left = '0';

            // Remet l'icône d'origine
            const icon = sidebarToggle.querySelector('i');
            icon.classList.remove('bx-x');
            icon.classList.add('bxs-info-circle');
        });</script>
@endsection