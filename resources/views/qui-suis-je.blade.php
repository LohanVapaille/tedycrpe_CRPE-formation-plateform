@extends('layouts.app')

@section('title', $content['titre_principal'] ?? 'Qui suis-je ?')
@section('meta_description',
'Préparez le CRPE avec Tedy Florette grâce à des cours, des ressources et un accompagnement personnalisé.')

@section('content')
    <main class="about-section">
        <div class="about-container">

            <div class="about-grid">

                <div class="about-image-wrapper">
                    <div class="about-shape-bg"></div>
                    @if(isset($content['photo_profil']))
                        <img src="{{ asset($content['photo_profil']) }}" alt="Tedy Florette">
                    @else
                        <img src="{{ asset('img/photo/phototedy.jpg') }}" alt="Avatar par défaut">
                    @endif
                </div>

                <div class="about-text-wrapper">
                    <h1>{{ $content['titre_principal'] ?? 'Qui suis-je ?' }}</h1>

                    <div class="about-paragraphs">
                        <p class="lead-text">{{ $content['paragraphe_1'] ?? '' }}</p>
                        <p>{{ $content['paragraphe_2'] ?? '' }}</p>
                        <p>{{ $content['paragraphe_3'] ?? '' }}</p>
                    </div>

                    <a href="https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ2QBMwbgmWglOJHrCIE-S12yVzNahg0GGqPIl1MA4xwXlgmRzXVDucuTl8lkXu7zH_U0ekXkyAR"
                        target='_blank' class="btn-discover">Réserver un appel découverte</a>
                </div>

            </div>

            {{-- Section Mon expérience --}}
            <div class="about-experience">
                <h2>{{ $content['experience_titre'] ?? 'Mon expérience' }}</h2>

                <ul class="experience-list">
                    @for ($i = 1; $i <= 5; $i++)
                        @if(isset($content['experience_' . $i]))
                            <li class="experience-item">
                                <span class="experience-icon">🎓</span>
                                <p>{{ $content['experience_' . $i] }}</p>
                            </li>
                        @endif
                    @endfor
                </ul>
            </div>

        </div>
    </main>
@endsection