{{-- resources/views/admin/contenu/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Contenu du site')

@section('content')
    <div class="admin-header">
        <h1>Contenu du site</h1>
        <p>CRTL + F pour rechercher un mot</p>
    </div>

    @if(session('success'))
        <p style="color:green; margin-bottom:1rem;">{{ session('success') }}</p>
    @endif

    @foreach($contents as $page => $items)
        <details style="margin-bottom:1rem; background:white; border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,0.08);">
            <summary
                style="padding:1rem 1.5rem; font-weight:bold; font-size:1.05rem; cursor:pointer; list-style:none; display:flex; align-items:center; gap:0.5rem;">
                <i class='bx bx-chevron-right' style="transition:transform 0.2s;"></i>
                Page : {{ $page }}
            </summary>

            <div style="padding:0 1.5rem 1.5rem;">
                @foreach($items->groupBy('section') as $section => $sectionItems)
    @php $sectionItems = $sectionItems->sortBy('ordre'); @endphp
                    <h4
                        style="margin:1.25rem 0 0.75rem; color:#64748b; font-size:0.85rem; text-transform:uppercase; letter-spacing:1px;">
                        {{ $section }}
                    </h4>

                    @foreach($sectionItems as $content)
                        <div style="margin-bottom:1rem; padding:1rem; background:#f8fafc; border-radius:6px; border:1px solid #e2e8f0;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                                <span style="font-size:0.8rem; color:#94a3b8; font-family:monospace;">{{ $content->key }}</span>
                                <span style="font-size:0.75rem; color:#cbd5e1;">ordre : {{ $content->ordre }}</span>
                            </div>
                            <form action="{{ route('admin.contenu.update', $content) }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')

                                @if(str_contains($content->key, 'photo'))
    {{-- Upload image --}}
    @if($content->value)
        <div style="margin-bottom:0.5rem;">
            <img src="{{ asset($content->value) }}"
                style="height:80px; border-radius:4px; object-fit:cover;">
            <p style="font-size:0.75rem; color:#94a3b8; margin-top:0.25rem;">{{ $content->value }}</p>
        </div>
    @endif
    <input type="file" name="image" accept="image/*"
        style="width:100%; padding:0.5rem; border:1px solid #cbd5e1; border-radius:4px;">

@elseif(str_contains($content->value, '|'))
    {{-- Champ multi-parties — DOIT être avant le test strlen --}}
    @foreach(explode('|', $content->value) as $i => $part)
        <div style="margin-bottom:0.5rem;">
            <label style="font-size:0.75rem; color:#94a3b8;">Partie {{ $i + 1 }}</label>
            <input type="text" name="parts[]" value="{{ $part }}"
                style="width:100%; padding:0.5rem; border:1px solid #cbd5e1; border-radius:4px; font-size:0.9rem;">
        </div>
    @endforeach

@elseif(strlen($content->value) > 80)
    <textarea name="value" rows="3"
        style="width:100%; padding:0.5rem; border:1px solid #cbd5e1; border-radius:4px; font-size:0.9rem; resize:vertical;">{{ $content->value }}</textarea>

@else
    <input type="text" name="value" value="{{ $content->value }}"
        style="width:100%; padding:0.5rem; border:1px solid #cbd5e1; border-radius:4px; font-size:0.9rem;">
@endif

                                <button type="submit" class="btn btn-primary"
                                    style="margin-top:0.5rem; font-size:0.8rem; padding:0.35rem 0.75rem;">
                                    Enregistrer
                                </button>
                            </form>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </details>
    @endforeach

    @push('scripts')
        <script>
            // Rotate l'icône chevron quand on ouvre/ferme un details
            document.querySelectorAll('details').forEach(details => {
                const icon = details.querySelector('summary i');
                details.addEventListener('toggle', () => {
                    icon.style.transform = details.open ? 'rotate(90deg)' : 'rotate(0deg)';
                });
                // État initial
                if (details.open) icon.style.transform = 'rotate(90deg)';
            });
        </script>
    @endpush

@endsection