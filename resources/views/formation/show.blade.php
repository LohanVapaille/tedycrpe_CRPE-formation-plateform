@extends('layouts.gabaritsansfooter')

@section('title', $coursSelectionne->name)

@section('content')
    <div class="dashboard-layout fade-in">

        <!-- BARRE LATÉRALE : Liste des Matières et des Cours -->
        <aside class="dashboard-sidebar sidebar-menu-pagecours">
            <nav class="sidebar-menu menu-formation">
                @foreach($matieres as $matiere)
                    <div class="matiere-group">
                        <span class="matiere-title-sidebar">📁 {{ $matiere->name }}</span>
                        <ul class="sub-cours-list">
                            @foreach($matiere->cours as $c)
                                <li>
                                    <a href="{{ route('formation.cours', $c->id) }}"
                                        class="sub-menu-item {{ $coursSelectionne->id == $c->id ? 'active' : '' }}">
                                        📄 {{ $c->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>

            <!-- Bouton retour au tableau de bord globale -->
            <div style="margin-top: auto; padding-top: 1rem;">
                <a href="{{ route('formation.index') }}" class="menu-item">⬅️ Tableau de bord</a>
            </div>
        </aside>

        <!-- ZONE DE CONTENU CENTRAL (Le Cours) -->
        <main class="dashboard-main dynamic-course-content">

            <!-- En-tête du cours (Comme le bloc bleu "1. Mathématiques" de ta maquette) -->
            <div class="course-main-header">
                <h2>{{ $coursSelectionne->name }}</h2>
                <p>Matière : {{ $coursSelectionne->matiere->name }}</p>
                <div class="progress-container-mini">
                    <span>Progression du cours : 60%</span>
                    <div class="progress-bar-mini">
                        <div class="fill" style="width: 60%"></div>
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
                                <button type="submit" class="btn-bleuclair">Envoyer mon fichier</button>
                            </form>
                        </div>
                    @endif

                @endforeach
            </div>

            <!-- Bouton de bas de page -->
            <div class="course-footer-actions mb-5">
                <a href="{{ route('formation.cours', ['id' => $coursSelectionne->id + 1]) }}"
                    class="btn-dark-blue btncourssuivant  ">Cours
                    suivant</a>
            </div>
        </main>
    </div>
@endsection