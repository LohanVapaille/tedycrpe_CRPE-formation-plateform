{{-- resources/views/admin/sections/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Sections — ' . $cours->name)

@section('content')
    <div class="admin-header">
        <h1>Sections du cours : {{ $cours->name }}</h1>
        <a href="{{ route('admin.cours') }}" class="btn btn-secondary">← Retour</a>
    </div>

    @if(session('success'))
        <p style="color:green; margin-bottom:1rem;">{{ session('success') }}</p>
    @endif

    {{-- Formulaire ajout section --}}
    <div class="card" style="margin-bottom:2rem;">
        <h3 style="margin-bottom:1rem;">Ajouter une section</h3>
        <form action="{{ route('admin.sections.store', $cours) }}" method="POST">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 1fr 80px; gap:1rem; align-items:end;">
                <div class="form-group" style="margin:0;">
                    <label>Type</label>
                    <select name="type" required>
                        <option value="titre">Titre</option>
                        <option value="txt">Texte</option>
                        <option value="img">Image</option>
                        <option value="video">Vidéo</option>
                        <option value="upload">Upload</option>
                    </select>
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Titre</label>
                    <input type="text" name="titre" placeholder="Optionnel">
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Ordre</label>
                    <input type="number" name="ordre" value="{{ $sections->count() + 1 }}" required>
                </div>
            </div>
            <div class="form-group" style="margin-top:1rem;">
                <label>Contenu</label>
                <textarea name="contenu" rows="3" placeholder="Texte, URL vidéo, chemin image..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    {{-- Liste des sections --}}
    <table id="sections-table">
        <thead>
            <tr>
                <th>⠿</th>
                <th>Ordre</th>
                <th>Type</th>
                <th>Titre</th>
                <th>Contenu</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="sortable-sections">
            @foreach($sections as $section)
                <tr data-id="{{ $section->id }}">
                    <td style="cursor:grab;">⠿</td>
                    <td>{{ $section->ordre }}</td>
                    <td><span
                            style="background:#e2e8f0; padding:2px 8px; border-radius:4px; font-size:0.8rem;">{{ $section->type }}</span>
                    </td>
                    <td>{{ $section->titre ?? '—' }}</td>
                    <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        {{ $section->contenu ?? '—' }}</td>
                    <td style="display:flex; gap:0.5rem;">
                        <button class="btn btn-secondary edit-section-btn" data-id="{{ $section->id }}"
                            data-type="{{ $section->type }}" data-titre="{{ $section->titre }}"
                            data-contenu="{{ $section->contenu }}" data-ordre="{{ $section->ordre }}">
                            <i class='bx bx-edit'></i>
                        </button>
                        <form action="{{ route('admin.sections.destroy', $section) }}" method="POST"
                            onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger"><i class='bx bx-trash'></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Modal édition --}}
    <div id="edit-modal"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
        <div class="card" style="width:500px;">
            <h3 style="margin-bottom:1rem;">Modifier la section</h3>
            <form id="edit-form" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="edit-type">
                        <option value="titre">Titre</option>
                        <option value="txt">Texte</option>
                        <option value="img">Image</option>
                        <option value="video">Vidéo</option>
                        <option value="upload">Upload</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Titre</label>
                    <input type="text" name="titre" id="edit-titre">
                </div>
                <div class="form-group">
                    <label>Contenu</label>
                    <textarea name="contenu" id="edit-contenu" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Ordre</label>
                    <input type="number" name="ordre" id="edit-ordre">
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <button type="button" id="close-modal" class="btn btn-secondary">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
        <script>
            const baseUrl = "{{ url('') }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Drag & drop pour réordonner
            const sortable = Sortable.create(document.getElementById('sortable-sections'), {
                handle: 'td:first-child',
                animation: 150,
                onEnd: () => {
                    const rows = document.querySelectorAll('#sortable-sections tr');
                    const ordre = Array.from(rows).map((row, i) => ({ id: row.dataset.id, ordre: i + 1 }));
                    fetch(`${baseUrl}/admin/sections/reorder`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ ordre })
                    });
                }
            });

            // Modal édition
            const modal = document.getElementById('edit-modal');
            document.querySelectorAll('.edit-section-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    document.getElementById('edit-form').action = `${baseUrl}/admin/sections/${id}`;
                    document.getElementById('edit-type').value = btn.dataset.type;
                    document.getElementById('edit-titre').value = btn.dataset.titre || '';
                    document.getElementById('edit-contenu').value = btn.dataset.contenu || '';
                    document.getElementById('edit-ordre').value = btn.dataset.ordre;
                    modal.style.display = 'flex';
                });
            });

            document.getElementById('close-modal').addEventListener('click', () => modal.style.display = 'none');
        </script>
    @endpush
@endsection