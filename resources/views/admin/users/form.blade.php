{{-- resources/views/admin/users/form.blade.php --}}
@extends('layouts.admin')
@section('title', $user ? 'Modifier' : 'Créer un utilisateur')

@section('content')
    <div class="admin-header">
        <h1>{{ $user ? 'Modifier' : 'Nouvel utilisateur' }}</h1>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">← Retour</a>
    </div>

    <div class="card" style="max-width:600px;">
        <form action="{{ $user ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
            @csrf
            @if($user) @method('PUT') @endif

            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="firstname" value="{{ old('firstname', $user?->firstname) }}" required>
            </div>
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="name" value="{{ old('name', $user?->name) }}" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $user?->email) }}" required>
            </div>
            <div class="form-group">
                <label>Mot de passe {{ $user ? '(laisser vide pour ne pas changer)' : '' }}</label>
                <input type="password" name="password" {{ $user ? '' : 'required' }}>
            </div>

            <div class="form-group">
                <label>Académie</label>
                <input type="text" name="academie" value="{{ old('academie', $user?->academie) }}">
            </div>
            <div class="form-group">
                <label>Accès</label>
                <div class="form-group">

                    <select name="access" required>
                        @foreach(\App\Models\User::ACCES as $valeur => $libelle)
                            <option value="{{ $valeur }}" {{ old('access', $user?->access) === $valeur ? 'selected' : '' }}>
                                {{ $libelle }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="esttutor">Est tuteur


                </label>
                <input type="checkbox" name="is_tutor" value="1" {{ $user?->is_tutor ? 'checked' : '' }} id="esttutor">
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
@endsection