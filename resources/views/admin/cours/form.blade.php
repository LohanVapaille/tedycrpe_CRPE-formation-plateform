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
                <label for="id_matiere">Module</label>
                <select name="id_matiere" id="id_matiere" required>
                    <option value="">-- Sélectionner un module --</option>

                    @php
                        // On s'assure d'avoir toutes les matières groupées par leur module associé
                        // (Remplace $matieres par la variable contenant tes matières si nécessaire)
                        $matieresParModules = collect($matieres)->groupBy('module_associe');
                    @endphp

                    @foreach($matieresParModules as $nomModule => $listeMatieres)
                        {{-- optgroup permet de créer un sous-titre non sélectionnable à l'intérieur du select --}}
                        <optgroup
                            label="📁 {{ $nomModule === 'CRPE BAC + 3' ? 'Module CRPE BAC + 3' : 'Module CRPE BAC + 5' }}">
                            @foreach($listeMatieres as $matiere)
                                <option value="{{ $matiere->id }}" {{ (isset($cours) && $cours->id_matiere == $matiere->id) ? 'selected' : '' }}>
                                    {{ $matiere->name }}
                                </option>
                            @endforeach
                        </optgroup>
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