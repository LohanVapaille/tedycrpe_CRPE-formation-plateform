{{-- resources/views/admin/evenements/form.blade.php --}}
@extends('layouts.admin')
@section('title', $event ? 'Modifier' : 'Nouvel événement')

@section('content')
    <div class="admin-header">
        <h1>{{ $event ? 'Modifier l\'événement' : 'Nouvel événement' }}</h1>
        <a href="{{ route('admin.evenements') }}" class="btn btn-secondary">← Retour</a>
    </div>


    <div class="card" style="max-width:600px;">
        <form action="{{ $event ? route('admin.evenements.update', $event) : route('admin.evenements.store') }}"
            method="POST">
            @csrf
            @if($event) @method('PUT') @endif

            <div class="form-group">
                <label>Titre</label>
                <input type="text" name="title" value="{{ old('title', $event?->title) }}" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3">{{ old('description', $event?->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>Lien de visio <small style="color:#888; font-weight:normal;">(facultatif)</small></label>
                <input type="url" name="visio_link" value="{{ old('visio_link', $event?->visio_link) }}"
                    placeholder="https://meet.google.com/…" maxlength="500">
            </div>

            <div class="form-group">
                <label>Date de début</label>
                <input type="datetime-local" name="start_time"
                    value="{{ old('start_time', $event?->start_time?->format('Y-m-d\TH:i')) }}" required>
            </div>

            <div class="form-group">
                <label>Date de fin</label>
                <input type="datetime-local" name="end_time"
                    value="{{ old('end_time', $event?->end_time?->format('Y-m-d\TH:i')) }}" required>
            </div>

            <div class="form-group">
                <label>Visibilité</label>
                <select name="user_id" id="visibilite">
                    <option value="">🌐 Public (visible par tous)</option>
                    @foreach($eleves as $eleve)
                        <option value="{{ $eleve->id }}" {{ old('user_id', $event?->user_id) == $eleve->id ? 'selected' : '' }}>
                            🔒 Privé — {{ $eleve->firstname }} {{ $eleve->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
@endsection