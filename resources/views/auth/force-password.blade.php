@extends('layouts.app')

@section('title', 'Première Connexion')

@section('content')
    <div class="auth-container fade-in">
        <h2 class="auth-title">🔒 Sécurisez votre compte</h2>
        <p class="auth-subtitle">C'est votre première connexion. Veuillez choisir un nouveau mot de passe personnalisé.</p>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('auth.password.update') }}" method="POST" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" name="password" id="password" required placeholder="Minimum 6 caractères">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmez le mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    placeholder="Répétez le mot de passe">
            </div>

            <button type="submit" class="btn-submit">Valider et accéder à la formation</button>
        </form>
    </div>
@endsection