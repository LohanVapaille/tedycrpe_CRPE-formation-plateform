@extends('layouts.app')

@section('title', $content['titre_principal'] ?? 'Qui suis-je ?')

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

                    <a href="{{ route('agenda.index') }}" class="btn-discover">Découvrir le programme</a>
                </div>

            </div>

        </div>
    </main>
@endsection