<div class="matiere-group">
    {{-- La balise details s'ouvre automatiquement si le cours sélectionné est à l'intérieur --}}
    @php
        $hasActiveCourse = $matiere->cours->contains('id', $coursSelectionne->id);
    @endphp

    <details {{ $hasActiveCourse ? 'open' : '' }}>
        <summary class="matiere-title-sidebar">
            📁 {{ $matiere->name }} ({{ $matiere->progression ?? 0 }}%)
        </summary>

        <ul class="sub-cours-list">
            @foreach($matiere->cours as $c)
                <li>
                    <a href="{{ route('formation.cours', $c->id) }}"
                        class="sub-menu-item {{ $coursSelectionne->id == $c->id ? 'active' : '' }}">
                        📄 {{ $c->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </details>
</div>