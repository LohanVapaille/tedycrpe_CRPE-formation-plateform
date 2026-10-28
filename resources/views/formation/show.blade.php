@extends('layouts.gabaritsansfooter')

@section('title', $coursSelectionne->name)

@section('content')
    <div class="dashboard-layout fade-in">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <button class="dashboard-toggle-btn showone" id="sidebarToggle" aria-label="Liste des cours">
            <i class='dashboard-toggle-btn-i bx bx-list-ul'></i>
        </button>
        <aside class="dashboard-sidebar w-100 sidebar-menu-pagecours" id="dashboardSidebar">

            <nav class="sidebar-menu menu-formation">
                @if(Auth::user()->module() === 'all')
                    <details class="level-group" open>
                        <summary class="level-title-sidebar">🎓 CRPE Bac +5</summary>
                        <div class="level-content">
                            @foreach($matieres->where('module_associe', 'CRPE BAC + 5') as $matiere)
                                @include('partials.sidebar-matiere-item', ['matiere' => $matiere])
                            @endforeach
                        </div>
                    </details>

                    <details class="level-group" open>
                        <summary class="level-title-sidebar">🎓 CRPE Bac +3</summary>
                        <div class="level-content">
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

        <main class="dashboard-main dynamic-course-content">

            @if(session('success'))
                <div style="background: #f0fff4; border: 1px solid #38a169; color: #276749; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background: #fff5f5; border: 1px solid #e53e3e; color: #9b2c2c; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">
                    ✕ {{ session('error') }}
                </div>
            @endif

            <div class="course-main-header">
                <div>
                    <h2>{{ $coursSelectionne->name }}</h2>
                    <p>Matière : {{ $coursSelectionne->matiere->name }}</p>

                    @php
                        $matiereActuelle = $matieres->firstWhere('id', $coursSelectionne->id_matiere);
                        $progressionMatiere = $matiereActuelle->progression ?? 0;
                    @endphp
                </div>
                <div class="progress-container-mini">
                    <a href="{{ route('formation.index') }}" class="course-dashboard-btn">
                        <i class='bx bxs-dashboard'></i> Tableau de bord
                    </a>
                    <span class="matiere-progression">
                        Progression de la matière : {{ $progressionMatiere }}&nbsp;%
                    </span>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: {{ $progressionMatiere }}%"></div>
                    </div>
                </div>
            </div>

            <div class="course-sections-wrapper">
                @foreach($coursSelectionne->sections as $section)

                    @if($section->type === 'titre')
                        <h3 class="section-render-title">{{ $section->titre }}</h3>
                    @endif

                    @if($section->type === 'txt')
                        <div class="section-render-text">
                            @if($section->titre)
                            <h4>{{ $section->titre }}</h4> @endif
                            <p>{!! $section->contenu !!}</p>
                        </div>
                    @endif

                    @if($section->type === 'video')
                        @if($section->titre)
                        <h4>{{ $section->titre }}</h4> @endif
                        <div class="video-container"
                            style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                            <iframe src="{{ $section->youtube_embed_url }}"
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    @endif

                    @if($section->type === 'fichier')
    <div class="section-render-resource" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem; min-width: 0;">
            <div style="background: #ebf8ff; color: #3182ce; width: 48px; height: 48px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class='bx bx-file'></i>
            </div>
            <div>
                <h4 style="margin: 0 0 0.25rem 0; font-size: 1.05rem; color: #2d3748;">
                    {{ $section->titre ?? 'Ressource téléchargeable' }}
                </h4>
                <span style="font-size: 0.85rem; color: #718096;">
                    Document mis à disposition par le tuteur
                </span>
            </div>
        </div>

        @if($section->contenu)
            <a href="{{ route('formation.ressource', $section) }}" class="button-style btn-bleuclair resource-download-btn">
                <i class='bx bx-download'></i> Télécharger la ressource
            </a>
        @else
            <span style="color: #a0aec0; font-size: 0.85rem; font-style: italic;">Aucun fichier disponible</span>
        @endif
    </div>
@endif

                    @if($section->type === 'img')
                        <div class="section-render-image">
                            @if($section->titre)
                            <h4>{{ $section->titre }}</h4> @endif
                            <img src="{{ asset('storage/' . $section->contenu) }}" alt="{{ $section->titre ?? 'Image du cours' }}">
                        </div>
                    @endif

            
                    @if($section->type === 'zonedepot')
                        @php
                            $renduExistant = \App\Models\RenduDevoir::where('section_cours_id', $section->id)
                                ->where('user_id', Auth::id())
                                ->first();
                        @endphp

                        <div class="section-render-upload">
                            <div class="upload-header">
                                <div class="upload-icon">
                                    <i class='bx bx-cloud-upload'></i>
                                </div>
                                <div class="upload-title-block">
                                    <h4>{{ $section->titre ?? 'Rendu de devoir' }}</h4>
                                    @if($section->contenu)
                                        <p class="upload-instruction">{{ $section->contenu }}</p>
                                    @endif
                                </div>
                            </div>

                            @if($renduExistant)
                                <div class="current-rendu-box"
                                    style="background: #f7fafc; border: 1px solid #e2e8f0; padding: 1rem; border-radius: 8px; margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <i class='bx bx-file' style="font-size: 1.8rem; color: #387c88;"></i>
                                        <div>
                                            <span style="display: block; font-size: 0.85rem; color: #718096;">Votre fichier actuel :</span>
                                            <a href="{{ asset('storage/' . $renduExistant->chemin_fichier) }}" target="_blank"
                                                style="color: #387c88; font-weight: 600; text-decoration: none; hover:underline;">
                                                {{ $renduExistant->nom_origine }}
                                            </a>
                                        </div>
                                    </div>
                                    <span style="background: #e6fffa; color: #234e52; font-size: 0.8rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 600;">
                                        ✓ Rendu le {{ $renduExistant->updated_at->format('d/m/Y à H:i') }}
                                    </span>
                                </div>
                            @endif

                            <form action="{{ route('devoirs.store', $section) }}" method="POST" enctype="multipart/form-data"
                                class="upload-box-form">
                                @csrf
                                <div class="drop-zone" id="drop-zone-{{ $section->id }}"
                                    style="border: 2px dashed #cbd5e0; background: #fafafa; border-radius: 10px; position: relative; transition: all 0.2s ease;">
                                    <label for="devoir-{{ $section->id }}" class="drop-zone-label"
                                        style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2.5rem 1.5rem; text-align: center; cursor: pointer; width: 100%;">

                                        <div class="drop-zone-prompt" style="pointer-events: none;">
                                            <i class='bx bx-file-blank icon-file' style="font-size: 2.2rem; color: #a0aec0; margin-bottom: 0.75rem;"></i>
                                            <span class="drop-text" style="display: block; font-size: 0.95rem; color: #2d3748; margin-bottom: 0.25rem;">
                                                @if($renduExistant)
                                                    Glissez-déposez un nouveau fichier ici pour <strong>remplacer le document actuel</strong>
                                                @else
                                                    Glissez-déposez votre fichier ici ou <strong>parcourez vos fichiers</strong>
                                                @endif
                                            </span>
                                            <span class="drop-specs" style="font-size: 0.8rem; color: #718096;">Formats acceptés : PDF, Word, Images (Max : 5 Mo)</span>
                                        </div>

                                        <div class="drop-zone-success" style="display: none; pointer-events: none;">
                                            <i class='bx bx-check-circle icon-success' style="font-size: 2.5rem; color: #2f855a; margin-bottom: 0.5rem;"></i>
                                            <span style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.25rem;">Nouveau document pris en compte !</span>
                                            <span class="file-name-display" style="font-size: 0.95rem; color: #387c88; font-style: italic; font-weight: 600;"></span>
                                        </div>

                                    </label>
                                    <input type="file" name="devoir" id="devoir-{{ $section->id }}" class="file-input-hidden"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" required>
                                </div>

                                <div class="upload-actions">
                                    <button type="submit" class="button-style btn-bleuclair">
                                        <i class='bx bx-send'></i>
                                        {{ $renduExistant ? 'Modifier mon devoir' : 'Envoyer mon devoir' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                @endforeach
            </div>

            <div class="course-footer-actions mb-5-courspage">
                <form action="{{ route('formation.cours.terminer', $coursSelectionne->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="button-style btn-dark-blue">
                        Cours suivant et valider
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Liens vers les flashcards (artifacts claude.ai) affichés comme un bouton "Ouvrir les flashcards".
        // claude.ai interdit l'affichage dans un iframe : on ouvre dans un nouvel onglet.
        document.querySelectorAll('.section-render-text a[href*="claude.ai/artifact"], .section-render-text a[href*="claude.site"]').forEach(lien => {
            const texte = lien.textContent.trim();
            const libelle = (!texte || texte.startsWith('http')) ? 'Ouvrir les flashcards' : texte;

            lien.classList.add('btn-flashcards');
            lien.target = '_blank';
            lien.rel = 'noopener';
            lien.innerHTML = "<i class='bx bx-layer'></i><span></span><i class='bx bx-link-external btn-flashcards-ext'></i>";
            lien.querySelector('span').textContent = libelle;
        });

        document.querySelectorAll('.file-input-hidden').forEach(input => {
            input.addEventListener('change', function () {
                const dropZone = this.closest('.drop-zone');
                const promptBlock = dropZone.querySelector('.drop-zone-prompt');
                const successBlock = dropZone.querySelector('.drop-zone-success');
                const fileNameDisplay = dropZone.querySelector('.file-name-display');

                if (this.files && this.files.length > 0) {
                    const fileName = this.files[0].name;

                    promptBlock.style.display = 'none';
                    successBlock.style.display = 'block';
                    fileNameDisplay.textContent = fileName;

                    dropZone.style.borderColor = '#2f855a';
                    dropZone.style.backgroundColor = '#f0fff4';
                } else {
                    // Correction de la coquille promptBlock.style.style ici
                    promptBlock.style.display = 'block';
                    successBlock.style.display = 'none';
                    dropZone.style.borderColor = '#cbd5e0';
                    dropZone.style.backgroundColor = '#fafafa';
                }
            });
        });
    </script>

    <script>
        sidebarToggle?.addEventListener('click', () => {
            dashboardSidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('active');
            document.body.classList.toggle('no-scroll');
            const isOpen = dashboardSidebar.classList.contains('open');
            // Bouton collé au bord du menu ouvert, sans sortir de l'écran (menu pleine largeur sur mobile)
            sidebarToggle.style.left = isOpen
                ? (dashboardSidebar.offsetWidth - 56) + 'px' // croix dans le coin haut droit du menu
                : '0';
            sidebarToggle.classList.toggle('is-open', isOpen);

            const icon = sidebarToggle.querySelector('i');
            if (isOpen) {
                icon.classList.remove('bx-list-ul');
                icon.classList.add('bx-x');
            } else {
                icon.classList.remove('bx-x');
                icon.classList.add('bx-list-ul');
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
            icon.classList.add('bx-list-ul');
        });
    </script>
@endsection