@extends('layouts.app')
@section('title', 'Formation CRPE')

@section('content')

    {{-- HERO --}}
    <div class="crpe-hero">
        <div class="crpe-hero-overlay">
            <h1 class="w-correct">{{ $contents['hero']->firstWhere('key', 'titre')?->value }}</h1>
        </div>
    </div>

    {{-- SECTION FORMATION --}}
    <div class="crpe-section light-bg">
        <div class="crpe-container w-correct">
            <h2>{{ $contents['formation']->firstWhere('key', 'titre')?->value }}</h2>
            <div class="crpe-text-block">
                <p>{{ $contents['formation']->firstWhere('key', 'paragraphe_1')?->value }}</p>
                <p>{{ $contents['formation']->firstWhere('key', 'paragraphe_2')?->value }}</p>
            </div>
            <a href="" class="crpe-btn btn-darkblue">
                {{ $contents['formation']->firstWhere('key', 'btn_texte')?->value }}
            </a>
        </div>
    </div>

    {{-- SECTION ACCOMPAGNEMENT --}}
    <div class="crpe-section dark-teal-bg">
        <div class="w-correct crpe-container">
            <h2>{{ $contents['accompagnement']->firstWhere('key', 'titre')?->value }}</h2>
            <div class="crpe-grid-steps">
                @foreach($contents['accompagnement']->filter(fn($c) => str_starts_with($c->key, 'etape_'))->sortBy('ordre') as $etape)
                    @php [$titre, $texte] = explode('|', $etape->value); @endphp
                    <div class="step-card">
                        <h3>{{ $titre }}</h3>
                        <p>{{ $texte }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SECTION PROGRESSION --}}
    <div class="crpe-section light-bg">
        <div class="crpe-container relative-container w-correct">
            <h2>{{ $contents['progression']->firstWhere('key', 'titre')?->value }}</h2>
            <div class="crpe-text-block">
                <p>{{ $contents['progression']->firstWhere('key', 'paragraphe')?->value }}</p>
            </div>

            <div class="process-flow">
                @foreach($contents['progression']->filter(fn($c) => str_starts_with($c->key, 'etape_'))->sortBy('ordre') as $index => $etape)
                    @php [$icone, $texte, $border] = explode('|', $etape->value); @endphp
                    @if($index > 0)
                        <div class="flow-arrow">➔</div>
                    @endif
                    <div class="flow-step {{ $border }}">
                        <span class="flow-icon">{{ $icone }}</span>
                        <p>{{ $texte }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection