@php
    $hasActiveCourse = $matiere->cours->contains('id', $coursSelectionne->id);
    $progression = $matiere->progression ?? 0;
@endphp

<div class="matiere-group {{ $hasActiveCourse ? 'is-current' : '' }}">
    <details {{ $hasActiveCourse ? 'open' : '' }}>
        <summary class="matiere-title-sidebar">
            <span class="matiere-label">{{ $matiere->name }}</span>
            <span class="matiere-badge {{ $progression >= 100 ? 'done' : ($progression > 0 ? 'started' : 'zero') }}">
                @if($progression >= 100)
                    <i class='bx bx-check'></i>
                @else
                    {{ $progression }}&nbsp;%
                @endif
            </span>
            <i class='bx bx-chevron-right matiere-chevron'></i>
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
