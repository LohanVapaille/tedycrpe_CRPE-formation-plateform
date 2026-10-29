{{-- resources/views/admin/users/form.blade.php --}}
@extends('layouts.admin')
@section('title', $user ? 'Modifier' : 'Créer un utilisateur')

@section('content')
    <div class="admin-header">
        <div>
            <h1>{{ $user ? 'Modifier ' . $user->firstname . ' ' . $user->name : 'Nouvel utilisateur' }}</h1>
            @if($user)
                <p class="admin-subtitle">Inscrit le {{ $user->created_at?->format('d/m/Y') }}</p>
            @endif
        </div>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary"><i class='bx bx-arrow-back'></i> Retour</a>
    </div>

    <div class="card" style="max-width:640px;">
        <form action="{{ $user ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
            @csrf
            @if($user) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-sm-6 form-group mb-0">
                    <label for="firstname">Prénom</label>
                    <input type="text" id="firstname" name="firstname" value="{{ old('firstname', $user?->firstname) }}" required>
                </div>
                <div class="col-sm-6 form-group mb-0">
                    <label for="name">Nom</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user?->name) }}" required>
                </div>
            </div>

            <div class="form-group mt-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user?->email) }}" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" autocomplete="new-password" {{ $user ? '' : 'required' }}>
                @if($user)
                    <p class="form-hint">Laisser vide pour ne pas changer le mot de passe.</p>
                @endif
            </div>

            <div class="form-group">
                <label for="academie">Académie</label>
                <input type="text" id="academie" name="academie" value="{{ old('academie', $user?->academie) }}">
            </div>
            <div class="form-group">
                <label for="access">Accès</label>
                <select name="access" id="access" required>
                    @foreach(\App\Models\User::ACCES as $valeur => $libelle)
                        <option value="{{ $valeur }}" {{ old('access', $user?->access) === $valeur ? 'selected' : '' }}>
                            {{ $libelle }}</option>
                    @endforeach
                </select>
                <p class="form-hint">Les formules « E-learning » donnent accès aux mêmes cours, sans le groupe WhatsApp.</p>
            </div>
            <div class="form-group">
                <label for="esttutor" class="form-check-inline">
                    <input type="checkbox" name="is_tutor" value="1" {{ old('is_tutor', $user?->is_tutor) ? 'checked' : '' }} id="esttutor">
                    Est tuteur <span class="form-hint m-0">(accès au backoffice)</span>
                </label>
            </div>

            <div style="display:flex; gap:0.5rem;">
                <button type="submit" class="btn btn-primary"><i class='bx bx-save'></i> Enregistrer</button>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
@endsection
