{{-- Modules d'un niveau, regroupés en blocs Leçon / EPS / Entretien --}}
@foreach(\App\Models\Matiere::parBloc($matieres->values()) as $groupe)
    @if($groupe['titre'])
        <span class="sidebar-bloc-title">{{ $groupe['titre'] }}</span>
    @endif
    @foreach($groupe['matieres'] as $matiere)
        @include('partials.sidebar-matiere-item', ['matiere' => $matiere])
    @endforeach
@endforeach
