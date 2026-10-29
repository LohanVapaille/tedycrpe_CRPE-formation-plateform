{{-- resources/views/admin/blocs/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Blocs')

@section('content')
    <div class="admin-header">
        <h1>Blocs</h1>
    </div>


    <p style="color:#64748b; margin-bottom:1rem;">
        Les blocs regroupent les modules dans l'espace élève (tableau de bord et menu des cours), sous un titre comme
        « Leçon », « EPS » ou « Entretien ». Choisissez le bloc de chaque module dans
        <a href="{{ route('admin.formations') }}">Modules</a>. Glissez un bloc par la poignée
        <i class='bx bx-grid-vertical'></i> pour changer son ordre d'affichage.
    </p>

    <div class="card" style="max-width:700px; margin-bottom:1.5rem;">
        <form action="{{ route('admin.blocs.store') }}" method="POST" style="display:flex; gap:0.5rem; align-items:flex-end;">
            @csrf
            <div class="form-group" style="flex:1; margin:0;">
                <label>Nouveau bloc</label>
                <input type="text" name="name" placeholder="Ex. Oral" maxlength="50" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class='bx bx-plus'></i> Ajouter</button>
        </form>
    </div>

    <div class="table-responsive" style="max-width:900px;">
        <table>
            <thead>
                <tr>
                    <th style="width:70px;">Ordre</th>
                    <th>Nom</th>
                    <th>Modules</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody id="sortable-blocs">
                @forelse($blocs as $bloc)
                    <tr data-id="{{ $bloc->id }}">
                        <td class="drag-handle" title="Glisser pour réordonner" style="cursor:grab; white-space:nowrap;">
                            <i class='bx bx-grid-vertical' style="color:#94a3b8; font-size:1.2rem; vertical-align:middle;"></i>
                            <span class="ordre-value">{{ $loop->iteration }}</span>
                        </td>
                        <td>
                            <form id="rename-{{ $bloc->id }}" action="{{ route('admin.blocs.update', $bloc) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="text" name="name" value="{{ $bloc->name }}" maxlength="50" required
                                    style="width:100%; padding:0.4rem 0.6rem; border:1px solid #cbd5e1; border-radius:4px;">
                            </form>
                        </td>
                        <td>{{ $bloc->matieres_count }} module{{ $bloc->matieres_count > 1 ? 's' : '' }}</td>
                        <td class="col-actions">
                            <div class="table-actions">
                                <button type="submit" form="rename-{{ $bloc->id }}" class="btn btn-secondary btn-icon" title="Enregistrer le nom">
                                    <i class='bx bx-save'></i>
                                </button>
                                <form action="{{ route('admin.blocs.destroy', $bloc) }}" method="POST"
                                    onsubmit="return confirm('Supprimer le bloc « {{ addslashes($bloc->name) }} » ? Ses modules ne sont pas supprimés : ils apparaîtront dans « Autres ».')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-icon" title="Supprimer"><i class='bx bx-trash'></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:2rem;">Aucun bloc. Les modules s'affichent alors sans regroupement.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="ordre-toast"
        style="display:none; position:fixed; bottom:24px; left:50%; transform:translateX(-50%); background:#1e293b; color:#fff; padding:0.6rem 1.2rem; border-radius:8px; font-size:0.9rem; z-index:2000;">
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
        <script>
            const toast = document.getElementById('ordre-toast');

            function afficherToast(message, erreur = false) {
                toast.textContent = message;
                toast.style.background = erreur ? '#dc2626' : '#1e293b';
                toast.style.display = 'block';
                clearTimeout(toast._timer);
                toast._timer = setTimeout(() => toast.style.display = 'none', 2000);
            }

            const liste = document.getElementById('sortable-blocs');
            Sortable.create(liste, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: () => {
                    const lignes = liste.querySelectorAll('tr[data-id]');
                    const ids = Array.from(lignes).map(tr => tr.dataset.id);

                    fetch("{{ route('admin.blocs.reorder') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ ids })
                    })
                        .then(res => {
                            if (!res.ok) throw new Error();
                            lignes.forEach((tr, i) => tr.querySelector('.ordre-value').textContent = i + 1);
                            afficherToast('Ordre enregistré ✓');
                        })
                        .catch(() => afficherToast("Erreur : l'ordre n'a pas été enregistré", true));
                }
            });
        </script>
    @endpush
@endsection
