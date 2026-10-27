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

    <p style="color:#64748b; margin-bottom:1rem;">
        <i class='bx bx-move-vertical'></i> Glissez un cours par la poignée <i class='bx bx-grid-vertical'></i> pour changer son ordre dans la matière : l'ordre est enregistré automatiquement.
    </p>

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
            @forelse($coursParModules as $nomModule => $listeCours)
                <tbody>
                    <tr style="background: #1e293b; color: #ffffff;">
                        <td colspan="4" class="titrecrpeadmintableau"
                            style="font-weight: bold; padding: 1rem; text-transform: uppercase; font-size: 1.1rem; letter-spacing: 0.5px;">
                            📁 {{ $nomModule === 'CRPE BAC + 3' ? 'Module BAC + 3' : 'Module CRPE BAC + 5' }}
                        </td>
                    </tr>
                </tbody>

                @foreach($listeCours->groupBy('id_matiere') as $idMatiere => $liste)
                    @php
                        $nomMatiere = $liste->first()->matiere->name ?? 'Matière inconnue';
                        $groupeId = 'groupe-' . ($nomModule === 'CRPE BAC + 3' ? 'bac3' : 'bac5') . '-' . $idMatiere;
                    @endphp

                    <tbody>
                        <tr style="cursor:pointer; background:#e2e8f0;" onclick="toggleGroupe('{{ $groupeId }}', this)">
                            <td colspan="4" style="font-weight:bold; padding:0.75rem 1rem; color: #334155;">
                                <span class="toggle-icon">▶</span> {{ $nomMatiere }}
                            </td>
                        </tr>
                    </tbody>

                    {{-- Un tbody par matière : le glisser-déposer reste limité aux cours de cette matière --}}
                    <tbody id="{{ $groupeId }}" class="sortable-cours" style="display: none;">
                        @foreach($liste as $c)
                            <tr data-id="{{ $c->id }}">
                                <td class="drag-handle" title="Glisser pour réordonner"
                                    style="cursor: grab; white-space: nowrap;">
                                    <i class='bx bx-grid-vertical' style="color:#94a3b8; font-size:1.2rem; vertical-align:middle;"></i>
                                    <span class="ordre-value">{{ $c->ordre }}</span>
                                </td>
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
                    </tbody>
                @endforeach
            @empty
                <tbody>
                    <tr>
                        <td colspan="4" class="no-data" style="text-align: center; padding: 2rem;">Aucun cours disponible.</td>
                    </tr>
                </tbody>
            @endforelse
        </table>
    </div>

    <div id="ordre-toast"
        style="display:none; position:fixed; bottom:24px; left:50%; transform:translateX(-50%); background:#1e293b; color:#fff; padding:0.6rem 1.2rem; border-radius:8px; font-size:0.9rem; z-index:2000;">
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
        <script>
            function toggleGroupe(groupeId, headerRow) {
                const groupe = document.getElementById(groupeId);
                const icon = headerRow.querySelector('.toggle-icon');
                if (!groupe) return;

                const isVisible = groupe.style.display !== 'none';
                groupe.style.display = isVisible ? 'none' : '';
                icon.textContent = isVisible ? '▶' : '▼';
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const toast = document.getElementById('ordre-toast');

            function afficherToast(message, erreur = false) {
                toast.textContent = message;
                toast.style.background = erreur ? '#dc2626' : '#1e293b';
                toast.style.display = 'block';
                clearTimeout(toast._timer);
                toast._timer = setTimeout(() => toast.style.display = 'none', 2000);
            }

            document.querySelectorAll('.sortable-cours').forEach(groupe => {
                Sortable.create(groupe, {
                    handle: '.drag-handle',
                    animation: 150,
                    onEnd: () => {
                        const lignes = groupe.querySelectorAll('tr[data-id]');
                        const ids = Array.from(lignes).map(tr => tr.dataset.id);

                        fetch("{{ route('admin.cours.reorder') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ ids })
                        })
                            .then(res => {
                                if (!res.ok) throw new Error();
                                // Met à jour les numéros affichés
                                lignes.forEach((tr, i) => tr.querySelector('.ordre-value').textContent = i + 1);
                                afficherToast('Ordre enregistré ✓');
                            })
                            .catch(() => afficherToast("Erreur : l'ordre n'a pas été enregistré", true));
                    }
                });
            });
        </script>
    @endpush
@endsection
