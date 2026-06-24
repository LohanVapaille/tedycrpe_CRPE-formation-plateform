@extends('layouts.app')

@section('title', 'Espace Formation')

@section('content')
    <div class="formation-dashboard fade-in">
        <div class="dashboard-header">
            <div>
                <h2>Bonjour, {{ Auth::user()->firstname }} 👋</h2>
                <p>Ravi de vous revoir dans votre espace de révision.</p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Se déconnecter</button>
            </form>
        </div>

        <div class="secure-box">
            <h3>🔒 Espace privé sécurisé</h3>
            <p>Si vous voyez cette page, c'est que la session Laravel fonctionne parfaitement et que vous êtes bien
                authentifié !</p>
        </div>
    </div>
@endsection