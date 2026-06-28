{{-- resources/views/admin/cours/form.blade.php --}}
@extends('layouts.admin')
@section('title', $cours ? 'Modifier' : 'Nouveau cours')

@section('content')
    <div class="admin-header">
        <h1>{{ $cours ? 'Modifier le cours' : 'Nouveau cours' }}</h1>
        <a href="{{ route('admin.cours') }}" class="btn btn-secondary">← Retour</a>
    </div>

    <div class="card" style="max-width:500px;">
        <form action="{{ $cours ? route('admin.cours.update', $cours) : route('admin.cours.store') }}" method="POST">
            @csrf
            @if($cours) @method('PUT') @endif

            <div class="form-group">
                <label>Nom du cours</label>
                <input type="text" name="name" value="{{ old('name', $cours?->name) }}" required>
            </div>
            <div class="form-group">
                <label>Matière</label>
                <select name="id_matiere" required>
                    <option value="">-- Choisir --</option>
                    @foreach($matieres as $m)
                        <option value="{{ $m->id }}" {{ old('id_matiere', $cours?->id_matiere) == $m->id ? 'selected' : '' }}>
                            {{ $m->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Ordre</label>
                <input type="number" name="ordre" value="{{ old('ordre', $cours?->ordre ?? 1) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
@endsection