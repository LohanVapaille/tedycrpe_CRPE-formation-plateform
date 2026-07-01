{{-- resources/views/admin/cours/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Cours')

@section('content')
    <div class="admin-header">
        <h1>Cours</h1>
        <a href="{{ route('admin.cours.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Nouveau cours
        </a>
    </div>

    @if(session('success'))
        <p style="color:green; margin-bottom:1rem;">{{ session('success') }}</p>
    @endif

    @php
        $coursParModules = collect($cours)->flatten()->groupBy(function ($c) {
            return $c->matiere->module_associe ?? 'Non défini';
        });
    @endphp

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Nom</th>
                    <th>Matière</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coursParModules as $nomModule => $listeCours)
                    <tr style="background: #1e293b; color: #ffffff;">
                        <td colspan="4" class="titrecrpeadmintableau"
                            style="font-weight: bold; padding: 1rem; text-transform: uppercase; font-size: 1.1rem; letter-spacing: 0.5px;">
                            📁 {{ $nomModule === 'CRPE BAC + 3' ? 'Module BAC + 3' : 'Module CRPE BAC + 5' }}
                        </td>
                    </tr>

                    @foreach($listeCours->groupBy('id_matiere') as $idMatiere => $liste)
                        @php
                            $nomMatiere = $liste->first()->matiere->name ?? 'Matière inconnue';
                            $groupeId = 'groupe-' . ($nomModule === 'CRPE BAC + 3' ? 'bac3' : 'bac5') . '-' . $idMatiere;
                        @endphp

                        <tr style="cursor:pointer; background:#e2e8f0;" onclick="toggleGroupe('{{ $groupeId }}', this)">
                            <td colspan="4" style="font-weight:bold; padding:0.75rem 1rem; color: #334155;">
                                {{-- Changement ici : On met la flèche vers la droite (▶) par défaut --}}
                                <span class="toggle-icon">▶</span> {{ $nomMatiere }}
                            </td>
                        </tr>

                        @foreach($liste as $c)
                            {{-- Changement ici : On ajoute style="display: none;" pour cacher la ligne par défaut --}}
                            <tr class="{{ $groupeId }}" style="display: none;">
                                <td>{{ $c->ordre }}</td>
                                <td>{{ $c->name }}</td>
                                <td>{{ $c->matiere->name }}</td>
                                <td style="display:flex; gap:0.5rem;">
                                    <a href="{{ route('admin.sections', $c) }}" class="btn btn-primary">
                                        <i class='bx bx-list-ul'></i> Sections
                                    </a>
                                    <a href="{{ route('admin.cours.edit', $c) }}" class="btn btn-secondary">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <form action="{{ route('admin.cours.destroy', $c) }}" method="POST"
                                        onsubmit="return confirm('Supprimer ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger"><i class='bx bx-trash'></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @empty
                    <tr>
                        <td colspan="4" class="no-data" style="text-align: center; padding: 2rem;">Aucun cours disponible.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script>
            function toggleGroupe(groupeId, headerRow) {
                const rows = document.querySelectorAll('.' + groupeId);
                const icon = headerRow.querySelector('.toggle-icon');
                if (rows.length === 0) return;

                // La logique reste la même, elle s'adapte à l'état initial masqué
                const isVisible = rows[0].style.display !== 'none';
                rows.forEach(row => row.style.display = isVisible ? 'none' : '');
                icon.textContent = isVisible ? '▶' : '▼';
            }
        </script>
    @endpush
@endsection