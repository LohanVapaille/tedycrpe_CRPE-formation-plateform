<div class="secure-matiere-card">
    <span class="matiere-number">{{ $matiere->name }}</span>
    @if($matiere->cours->isNotEmpty())
        <a href="{{ route('formation.cours', ['id' => $matiere->cours->first()->id]) }}" class="btn-action-course">
            Lire le cours
        </a>
    @else
        <span class="btn-action-course" style="opacity:0.5; cursor:not-allowed;">Bientôt disponible</span>
    @endif
    <span class="matiere-progression">{{ $matiere->progression ?? 0 }}&nbsp;% de progression</span>
</div>
