{{-- resources/views/admin/sections/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Sections — ' . $cours->name)

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <style>
        .ql-container {
            min-height: 150px;
            background: white;
        }

        .hidden-field {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="admin-header">
        <h1>Sections du cours : {{ $cours->name }}</h1>
        <a href="{{ route('admin.cours') }}" class="btn btn-secondary">← Retour</a>
    </div>


    {{-- Formulaire ajout section --}}
    <div class="card" style="margin-bottom:2rem;">
        <h3 style="margin-bottom:1rem;">Ajouter une section</h3>
        <form action="{{ route('admin.sections.store', $cours) }}" method="POST" enctype="multipart/form-data"
            id="create-form">
            @csrf
            <div style="display:grid; grid-template-columns:1fr 1fr 80px; gap:1rem; align-items:end;">
                <div class="form-group" style="margin:0;">
                    <label>Type</label>
                    <select name="type" id="create-type" required data-native>
                        <option value="titre">Titre</option>
                        <option value="txt">Texte</option>
                        <option value="img">Image</option>
                        <option value="video">Vidéo</option>
                        <option value="zonedepot">Zone de dépot</option>
                        <option value="fichier">Ressource / Fichier à télécharger</option>
                    </select>
                </div>
                <div class="form-group field-titre" style="margin:0;">
                    <label>Titre</label>
                    <input type="text" name="titre" placeholder="Optionnel">
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Ordre</label>
                    <input type="number" name="ordre" value="{{ $sections->count() + 1 }}" required>
                </div>
            </div>

            {{-- Zone dynamique de contenu pour l'ajout --}}
            <div class="form-group field-contenu" style="margin-top:1rem;">
                <label id="label-contenu">Contenu</label>

                <div id="editor-container-create" class="quill-editor-wrapper"></div>

                <textarea name="contenu" id="textarea-create" rows="3" class="form-control"></textarea>

                <input type="file" name="image" id="file-create" class="hidden-field" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Ajouter</button>
        </form>
    </div>

    <div class="table-responsive">
        {{-- Liste des sections --}}
        <table id="sections-table">
            <thead>
                <tr>
                    <th>⠿</th>
                    <th>Ordre</th>
                    <th>Type</th>
                    <th>Titre</th>
                    <th>Contenu</th>
                    <th class="col-actions">Actions</th>
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
                            {{ strip_tags($section->contenu) ?? '—' }}
                        </td>
                        <td class="col-actions">
                            <div class="table-actions">
                                <button class="btn btn-secondary edit-section-btn" data-id="{{ $section->id }}"
                                    data-type="{{ $section->type }}" data-titre="{{ $section->titre }}"
                                    data-contenu="{{ $section->contenu }}" data-ordre="{{ $section->ordre }}">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <form action="{{ route('admin.sections.destroy', $section) }}" method="POST"
                                    onsubmit="return confirm('Supprimer ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-icon"><i class='bx bx-trash'></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal édition --}}
    <div id="edit-modal"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
        <div class="card" style="width:500px; max-height: 90vh; overflow-y: auto;">
            <h3 style="margin-bottom:1rem;">Modifier la section</h3>
            <form id="edit-form" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="edit-type" data-native>
                        <option value="titre">Titre</option>
                        <option value="txt">Texte</option>
                        <option value="img">Image</option>
                        <option value="video">Vidéo</option>
                        <option value="zonedepot">Zone de dépôt</option>
                        <option value="fichier">Ressource / Fichier à télécharger</option>
                    </select>
                </div>
                <div class="form-group field-titre-edit">
                    <label>Titre</label>
                    <input type="text" name="titre" id="edit-titre">
                </div>

                {{-- Zone dynamique de contenu pour l'édition --}}
                <div class="form-group field-contenu-edit">
                    <label id="label-contenu-edit">Contenu</label>
                    <div id="editor-container-edit" class="quill-editor-wrapper"></div>
                    <textarea name="contenu" id="edit-contenu" rows="3"></textarea>
                    <input type="file" name="image" id="file-edit" class="hidden-field" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Ordre</label>
                    <input type="number" name="ordre" id="edit-ordre">
                </div>
                <div style="display:flex; gap:0.5rem; margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <button type="button" id="close-modal" class="btn btn-secondary">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
        <script>
            const baseUrl = "{{ url('') }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // --- INITIALISATION QUILL.JS ---
            const quillOptions = { theme: 'snow', modules: { toolbar: [['bold', 'italic', 'underline'], [{ 'list': 'ordered' }, { 'list': 'bullet' }], ['clean']] } };
            const quillCreate = new Quill('#editor-container-create', quillOptions);
            const quillEdit = new Quill('#editor-container-edit', quillOptions);

            // --- GESTION DES FORMULAIRES DYNAMIQUES ---
            function adapterFormulaire(type, context = 'create') {
                const suffix = context === 'create' ? 'create' : 'edit';

                const rowTitre = document.querySelector(context === 'create' ? '.field-titre' : '.field-titre-edit');
                const rowContenu = document.querySelector(context === 'create' ? '.field-contenu' : '.field-contenu-edit');
                const labelContenu = document.getElementById(`label-contenu-${suffix}`) || document.getElementById('label-contenu');
                const textarea = document.getElementById(context === 'create' ? 'textarea-create' : 'edit-contenu');
                const quillWrapper = document.getElementById(`editor-container-${suffix}`);
                const fileInput = document.getElementById(`file-${suffix}`);

                // Reset
                rowTitre.classList.remove('hidden-field');
                rowContenu.classList.remove('hidden-field');
                quillWrapper.classList.add('hidden-field');
                textarea.classList.remove('hidden-field');
                fileInput.classList.add('hidden-field');
                fileInput.removeAttribute('accept');

                switch (type) {
                    case 'titre':
                        rowContenu.classList.add('hidden-field');
                        textarea.value = '';
                        break;
                    case 'txt':
                        textarea.classList.add('hidden-field');
                        quillWrapper.classList.remove('hidden-field');
                        labelContenu.innerText = "Texte stylisé";
                        break;
                    case 'img':
                        textarea.classList.add('hidden-field');
                        fileInput.classList.remove('hidden-field');
                        fileInput.setAttribute('accept', 'image/*');
                        labelContenu.innerText = "Uploader l'image";
                        break;
                    case 'fichier':
                        textarea.classList.add('hidden-field');
                        fileInput.classList.remove('hidden-field');
                        fileInput.removeAttribute('accept'); // Accepte PDF, Word, ZIP, etc.
                        labelContenu.innerText = "Uploader le fichier / la ressource";
                        break;
                    case 'video':
                        labelContenu.innerText = "Lien de la vidéo YouTube";
                        textarea.placeholder = "https://www.youtube.com/watch?v=...";
                        break;
                    case 'zonedepot':
                        labelContenu.innerText = "Consignes du devoir";
                        textarea.placeholder = "Ex : Déposez votre compte-rendu au format PDF...";
                        break;
                }
            }

            // Événements pour le formulaire de création
            const selectCreate = document.getElementById('create-type');
            selectCreate.addEventListener('change', (e) => adapterFormulaire(e.target.value, 'create'));
            adapterFormulaire(selectCreate.value, 'create'); // Init au chargement

            // Événements pour le formulaire d'édition
            const selectEdit = document.getElementById('edit-type');
            selectEdit.addEventListener('change', (e) => adapterFormulaire(e.target.value, 'edit'));

            // Intercepter la soumission pour injecter le texte Quill dans le textarea soumis
            document.getElementById('create-form').addEventListener('submit', function () {
                if (selectCreate.value === 'txt') {
                    document.getElementById('textarea-create').value = quillCreate.root.innerHTML;
                }
            });
            document.getElementById('edit-form').addEventListener('submit', function () {
                if (selectEdit.value === 'txt') {
                    document.getElementById('edit-contenu').value = quillEdit.root.innerHTML;
                }
            });


            // --- DRAG & DROP (SORTABLE) ---
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

            // --- MODAL ÉDITION ---
            const modal = document.getElementById('edit-modal');
            document.querySelectorAll('.edit-section-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    const type = btn.dataset.type;
                    const contenu = btn.dataset.contenu || '';

                    document.getElementById('edit-form').action = `${baseUrl}/admin/sections/${id}`;
                    selectEdit.value = type;
                    document.getElementById('edit-titre').value = btn.dataset.titre || '';
                    document.getElementById('edit-ordre').value = btn.dataset.ordre;

                    // Configurer les champs selon le type actuel
                    adapterFormulaire(type, 'edit');

                    // Charger le contenu dans le bon champ
                    if (type === 'txt') {
                        quillEdit.root.innerHTML = contenu;
                    } else {
                        // Correction de la coquille ici (edit-contenu au lieu de edit-conténeu)
                        document.getElementById('edit-contenu').value = contenu;
                    }

                    modal.style.display = 'flex';
                });
            });

            document.getElementById('close-modal').addEventListener('click', () => modal.style.display = 'none');
        </script>
    @endpush
@endsection