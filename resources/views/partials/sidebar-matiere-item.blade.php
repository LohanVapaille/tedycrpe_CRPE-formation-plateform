@php $hasActiveCourse = $matiere->cours->contains('id', $coursSelectionne->id); @endphp

<div class="matiere-group">
    <details {{ $hasActiveCourse ? 'open' : '' }}>
        <summary class="matiere-title-sidebar">
            <span class="matiere-folder-icon">{{ $hasActiveCourse ? '📂' : '📁' }}</span>
            <span class="matiere-label">{{ $matiere->name }}</span>
            @if(($matiere->progression ?? 0) > 0)
                <span class="matiere-badge">{{ $matiere->progression }}%</span>
            @else
                <span class="matiere-badge zero">0%</span>
            @endif
        </summary>

        <ul class="sub-cours-list">
            @foreach($matiere->cours as $c)
                <li>
                    <a href="{{ route('formation.cours', $c->id) }}"
                        class="sub-menu-item {{ $coursSelectionne->id == $c->id ? 'active' : '' }}">
                        {{ $c->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </details>
</div>