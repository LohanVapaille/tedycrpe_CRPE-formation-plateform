{{-- resources/views/admin/formations/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Formations')

@section('content')
    <div class="admin-header">
        <h1>Matières</h1>
        <a href="{{ route('admin.formations.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Nouvelle matière
        </a>
    </div>

    @if(session('success'))
        <p style="color:green; margin-bottom:1rem;">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Ordre</th>
                <th>Nom</th>
                <th>Module</th>
                <th>Cours associés</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matieres as $module => $liste)
                <tr>
                    <td colspan="5" style="background:#e2e8f0; font-weight:bold; padding:0.75rem 1rem;">
                        {{ $module }}
                    </td>
                </tr>
                @foreach($liste as $matiere)
                    <tr>
                        <td>{{ $matiere->ordre }}</td>
                        <td>{{ $matiere->name }}</td>
                        <td>{{ $matiere->module_associe }}</td>
                        <td>{{ $matiere->cours->count() }} cours</td>
                        <td style="display:flex; gap:0.5rem;">
                            <a href="{{ route('admin.formations.edit', $matiere) }}" class="btn btn-secondary">
                                <i class='bx bx-edit'></i>
                            </a>
                            <form action="{{ route('admin.formations.destroy', $matiere) }}" method="POST"
                                onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger"><i class='bx bx-trash'></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@endsection