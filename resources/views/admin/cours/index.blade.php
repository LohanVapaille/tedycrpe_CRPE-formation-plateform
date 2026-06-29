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
                @foreach($cours as $groupe => $liste)
                    @php
                        [$module, $matiere] = explode('|||', $groupe);
                        $groupeId = 'groupe-' . $loop->index;
                    @endphp
                    <tr style="cursor:pointer; background:#e2e8f0;" onclick="toggleGroupe('{{ $groupeId }}', this)">
                        <td colspan="4" style="font-weight:bold; padding:0.75rem 1rem;">
                            <span class="toggle-icon">▼</span> {{ $module }} — {{ $matiere }}
                        </td>
                    </tr>
                    @foreach($liste as $c)
                        <tr class="{{ $groupeId }}">
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
            </tbody>
        </table>
    </div>
    @push('scripts')
        <script>
            function toggleGroupe(groupeId, headerRow) {
                const rows = document.querySelectorAll('.' + groupeId);
                const icon = headerRow.querySelector('.toggle-icon');
                const isVisible = rows[0]?.style.display !== 'none';
                rows.forEach(row => row.style.display = isVisible ? 'none' : '');
                icon.textContent = isVisible ? '▶' : '▼';
            }
        </script>
    @endpush

@endsection