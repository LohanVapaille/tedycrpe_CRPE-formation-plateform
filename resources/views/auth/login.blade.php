@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
    <div class="auth-container fade-in">
        <h2 class="auth-title">Connexion à l'espace de formation</h2>
        <p class="auth-subtitle">Veuillez entrer vos identifiants pour accéder à vos cours.</p>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    placeholder="exemple@tedycrpe.fr">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-submit">Se connecter</button>
        </form>
        <p>Si vous avez oublié votre mot de passe, veuillez contacter votre tuteur.</p>
    </div>
@endsection