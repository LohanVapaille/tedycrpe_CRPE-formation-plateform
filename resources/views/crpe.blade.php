@extends('layouts.app')
@section('title', 'Formation CRPE')
@section('meta_description',
'Préparez le CRPE avec Tedy Florette grâce à des cours, des ressources et un accompagnement personnalisé.')
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
            <a style="margin-top: 10px;display: inline-block;" class="welcome-btn btn-primary" target="_blank"
                href="https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ2QBMwbgmWglOJHrCIE-S12yVzNahg0GGqPIl1MA4xwXlgmRzXVDucuTl8lkXu7zH_U0ekXkyAR">Réserver
                un appel découverte</a>
        </div>
    </div>

    {{-- SECTION PRESTATIONS PROPOSÉES --}}
    {{-- SECTION PRESTATIONS PROPOSÉES --}}
    <div class="crpe-section dark-teal-bg">
        <div class="crpe-container w-correct">
            <h2 class="section-title-center">{{ $contents['prestations']->firstWhere('key', 'titre')?->value }}</h2>

            <div class="prestations-modern-grid">
                @foreach($contents['prestations']->filter(fn($c) => str_starts_with($c->key, 'etape_'))->sortBy('ordre') as $prestation)
                    <div class="prestation-modern-card">
                        <div class="prestation-icon-wrapper">
                            {{-- Icône check SVG élégante --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor" class="prestation-svg-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <div class="prestation-card-content">
                            {{-- "Titre|Texte" : titre en gras puis texte à la ligne (plus de "|" affiché) --}}
                            @php [$prestationTitre, $prestationTexte] = array_pad(explode('|', $prestation->value, 2), 2, ''); @endphp
                            @if(trim($prestationTexte) !== '')
                                <p><strong>{{ trim($prestationTitre) }}</strong><br>{{ trim($prestationTexte) }}</p>
                            @else
                                <p>{{ trim($prestationTitre) }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SECTION ACCOMPAGNEMENT --}}
    <div class="crpe-section light-bg">
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

            <div class="crpe-final-cta">
                <a class="welcome-btn btn-primary" target="_blank"
                    href="https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ2QBMwbgmWglOJHrCIE-S12yVzNahg0GGqPIl1MA4xwXlgmRzXVDucuTl8lkXu7zH_U0ekXkyAR">Réserver
                    un appel découverte</a>
            </div>
        </div>
    </div>

@endsection