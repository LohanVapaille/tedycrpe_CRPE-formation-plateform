{{-- resources/views/admin/formations/form.blade.php --}}
@extends('layouts.admin')
@section('title', $matiere ? 'Modifier' : 'Nouveau module')

@section('content')
    <div class="admin-header">
        <h1>{{ $matiere ? 'Modifier le module' : 'Nouveau module' }}</h1>
        <a href="{{ route('admin.formations') }}" class="btn btn-secondary">← Retour</a>
    </div>

    <div class="card" style="max-width:500px;">
        <form action="{{ $matiere ? route('admin.formations.update', $matiere) : route('admin.formations.store') }}"
            method="POST">
            @csrf
            @if($matiere) @method('PUT') @endif

            <div class="form-group">
                <label>Nom du module</label>
                <input type="text" name="name" value="{{ old('name', $matiere?->name) }}" required>
            </div>
            <div class="form-group">
                <label>Module associé</label>
                <select name="module_associe" required>
                    <option value="CRPE BAC + 5" {{ old('module_associe', $matiere?->module_associe) === 'CRPE BAC + 5' ? 'selected' : '' }}>CRPE BAC +5</option>
                    <option value="CRPE BAC + 3" {{ old('module_associe', $matiere?->module_associe) === 'CRPE BAC + 3' ? 'selected' : '' }}>CRPE BAC +3</option>
                </select>
            </div>
            <div class="form-group">
                <label>Bloc</label>
                <select name="bloc_id">
                    <option value="">Aucun</option>
                    @foreach(\App\Models\Bloc::orderBy('ordre')->get() as $bloc)
                        <option value="{{ $bloc->id }}" {{ (string) old('bloc_id', $matiere?->bloc_id) === (string) $bloc->id ? 'selected' : '' }}>{{ $bloc->name }}</option>
                    @endforeach
                </select>
                <small style="color:#64748b;">Regroupe le module sous ce titre dans l'espace élève.
                    <a href="{{ route('admin.blocs') }}">Gérer les blocs</a></small>
            </div>
            <div class="form-group">
                <label>Ordre</label>
                <input type="number" name="ordre" value="{{ old('ordre', $matiere?->ordre ?? 1) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
@endsection