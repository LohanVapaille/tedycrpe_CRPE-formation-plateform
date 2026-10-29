{{-- resources/views/admin/formations/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Formations')

@section('content')
    <div class="admin-header">
        <h1>Modules</h1>
        <a href="{{ route('admin.formations.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Nouveau module
        </a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Nom</th>
                    <th>Niveau</th>
                    <th>Bloc</th>
                    <th>Cours associés</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matieres as $module => $liste)
                    <tr>
                        <td colspan="6" style="background:#e2e8f0; font-weight:bold; padding:0.75rem 1rem;">
                            {{ $module }}
                        </td>
                    </tr>
                    @foreach($liste as $matiere)
                        <tr>
                            <td>{{ $matiere->ordre }}</td>
                            <td>
                                {{ $matiere->name }}
                                @if($matiere->acces_troisieme_voie)
                                    <span class="pill is-amber" title="Visible par les élèves 3ème voie/Interne">3ème voie</span>
                                @endif
                            </td>
                            <td>{{ $matiere->module_associe }}</td>
                            <td>{{ $matiere->bloc?->name ?? '—' }}</td>
                            <td>{{ $matiere->cours->count() }} cours</td>
                            <td class="col-actions">
                                <div class="table-actions">
                                    <a href="{{ route('admin.formations.edit', $matiere) }}" class="btn btn-secondary btn-icon">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <form action="{{ route('admin.formations.destroy', $matiere) }}" method="POST"
                                        onsubmit="return confirm('Supprimer ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-icon"><i class='bx bx-trash'></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endsection