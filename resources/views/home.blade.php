@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <div class="welcome-section">
        <h1>Nous formons et outillons celles et ceux qui font l’avenir de nos enfants.</h1>
        <a class="welcome-btn btn-primary" href="">Réserver un appel découverte</a>
    </div>
    <div class="matieres-grid">
        @foreach($matieres as $matiere)
            <div class="matiere-card">
                <span class="module-tag">{{ $matiere->module_associe }}</span>
                <h3 class="matiere-name">{{ $matiere->name }}</h3>

                <h4 class="cours-title">Chapitres / Cours :</h4>
                <ul class="cours-list">
                    @forelse($matiere->cours as $c)
                        <li class="cours-item">
                            <span class="cours-name">{{ $c->name }}</span>
                            <a href="#" class="btn-view">Voir le cours</a>
                        </li>
                    @empty
                        <li class="no-course">Aucun cours disponible pour le moment.</li>
                    @endforelse
                </ul>
            </div>
        @endforeach
    </div>
@endsection