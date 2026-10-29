@extends('layouts.admin')
@section('title', 'Rendus des Devoirs')

@section('content')
    <div class="admin-header">
        <div>
            <h1>Rendus des devoirs</h1>
            <p class="admin-subtitle">{{ $students->count() }} élève(s) ayant rendu au moins un devoir</p>
        </div>
    </div>

    @if($students->isNotEmpty())
        <div class="admin-toolbar">
            <div class="admin-search">
                <i class='bx bx-search'></i>
                <input type="search" id="devoirs-search" placeholder="Rechercher un élève…" autocomplete="off">
            </div>
        </div>
        <p class="admin-search-empty" id="devoirs-empty">Aucun élève ne correspond à la recherche.</p>
    @endif


    <div class="devoirs-container" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @forelse($students as $student)
            @php
                // On regroupe les rendus de cet étudiant par le module associé à la matière du cours
                $rendusParModules = $student->rendus->groupBy(function ($rendu) {
                    return $rendu->section->cours->matiere->module_associe ?? 'Non défini';
                });
            @endphp

            <div class="card devoir-student" data-search="{{ $student->firstname }} {{ $student->name }} {{ $student->email }}"
                style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div style="cursor: pointer; display: flex; justify-content: space-between; align-items: center;"
                    onclick="toggleStudent('student-{{ $student->id }}', this)">
                    <h3 style="margin: 0; color: #1e293b;">
                        <span class="toggle-icon" style="margin-right: 0.5px;">▶</span> {{ $student->firstname }} {{ $student->name }}
                        <span style="font-size: 0.9rem; color: #64748b; font-weight: normal;">({{ $student->email }})</span>
                    </h3>
                    <span
                        style="background: #e2e8f0; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; color: #475569;">
                        {{ $student->rendus->count() }} rendu(s)
                    </span>
                </div>

                <div id="student-{{ $student->id }}"
                    style="display: none; margin-top: 1.5rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">

                    @foreach($rendusParModules as $nomModule => $rendusDuModule)
                        <div class="module-block" style="margin-bottom: 1.5rem;">
                            <h4
                                style="margin: 0 0 0.75rem 0; color: #2c6e7a; text-transform: uppercase; font-size: 0.95rem; letter-spacing: 0.5px;">
                                📁 {{ $nomModule === 'Non défini' ? 'Module non défini' : 'Module ' . $nomModule }}
                            </h4>

                            <div class="table-responsive">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                                            <th style="padding: 0.5rem 1rem; color: #64748b; font-size: 0.85rem;">Cours concerné
                                            </th>
                                            <th style="padding: 0.5rem 1rem; color: #64748b; font-size: 0.85rem;">Nom du fichier
                                                d'origine</th>
                                            <th style="padding: 0.5rem 1rem; color: #64748b; font-size: 0.85rem;">Date de rendu</th>
                                            <th
                                                style="padding: 0.5rem 1rem; color: #64748b; font-size: 0.85rem; text-align: right;">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rendusDuModule as $rendu)
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td style="padding: 0.75rem 1rem; font-weight: 500; color: #334155;">
                                                    {{ $rendu->section->cours->name ?? 'Cours inconnu' }}
                                                </td>
                                                <td style="padding: 0.75rem 1rem; color: #475569; font-size: 0.9rem;">
                                                    {{ $rendu->nom_origine }}
                                                </td>
                                                <td style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.9rem;">
                                                    Le {{ $rendu->created_at->format('d/m/Y à H:i') }}
                                                </td>
                                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                                    <a href="{{ route('admin.devoirs.download', $rendu) }}" class="btn btn-primary"
                                                        style="padding: 4px 10px; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 4px;">
                                                        <i class='bx bx-download'></i> Télécharger
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        @empty
            <div class="card" style="text-align: center; padding: 3rem; background: white; border-radius: 8px;">
                <p style="color: #64748b; margin: 0;">Aucun devoir n'a été rendu pour le moment.</p>
            </div>
        @endforelse
    </div>

    @push('scripts')
        <script>
            document.getElementById('devoirs-search')?.addEventListener('input', (e) => {
                const normaliser = (s) => [...s.normalize('NFD')]
                    .filter((c) => c.charCodeAt(0) < 0x300 || c.charCodeAt(0) > 0x36f).join('').toLowerCase();
                const termes = normaliser(e.target.value.trim()).split(/\s+/).filter(Boolean);
                let visibles = 0;
                document.querySelectorAll('.devoir-student').forEach((card) => {
                    const ok = termes.every((t) => normaliser(card.dataset.search).includes(t));
                    card.style.display = ok ? '' : 'none';
                    if (ok) visibles++;
                });
                document.getElementById('devoirs-empty').style.display = visibles ? 'none' : 'block';
            });

            function toggleStudent(studentId, headerDiv) {
                const container = document.getElementById(studentId);
                const icon = headerDiv.querySelector('.toggle-icon');
                if (!container) return;

                const isVisible = container.style.display !== 'none';
                container.style.display = isVisible ? 'none' : 'block';
                icon.textContent = isVisible ? '▶' : '▼';
            }
        </script>
    @endpush
@endsection