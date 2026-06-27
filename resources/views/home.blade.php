@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <div class="welcome-section ">
        <div class="w-correct">
        <h1>{{ $contents->firstWhere('key', 'title')->value ?? 'Texte de présentation par défaut.' }}</h1>
        
        <a class="welcome-btn btn-primary" href="">Réserver un appel découverte</a>
    </div>
</div>

  

    <div class="presentation-tedy w-correct">
        <div class="presentation-text">
            <h2>Tedy Florette, formateur CRPE</h2>
            <p>{{ $contents->firstWhere('key', 'subtitle')->value ?? 'Texte de présentation par défaut.' }}</p>
            <a class="btn-primary" href="">Réserver un appel découverte</a>
        </div>
        <div class="presentation-image">
            <img src="{{ $contents->firstWhere('key', 'photo-tedy')->value ?? 'img/photo/phototedy.png' }}" alt="Tedy Florette">
            <p>Tedy Florette, formateur CRPE</p>
        </div>
    </div>

      <div class="stats-container-accueil">
        @foreach($contents->where('section', 'stats')->sortBy('ordre') as $stat)
            @php 
                // Si la valeur ne contient pas de "|", on met une valeur par défaut pour éviter le bug
                $parts = str_contains($stat->value, '|') ? explode('|', $stat->value) : [$stat->value, ''];
                $chiffre = $parts[0];
                $texte = $parts[1];
            @endphp
            <div class="stat-box w">
                <span class="stat-number">{{ $chiffre }}</span>
                @if(!empty($texte))
                    <p class="stat-title">{{ $texte }}</p>
                @endif
            </div>
        @endforeach
    </div>

    <div class="card-cta-container">
        @foreach($contents->where('section', 'cards')->sortBy('ordre') as $card)
            @php 
                // On extrait : Titre | Icône Boxicons | Lien de redirection
                [$titre, $icone, $lien] = explode('|', $card->value);
            @endphp
            <a href="{{ route($lien) }}" class="card-cta-link">
                <div class="card-cta">
                    <h3>{{ $titre }}</h3>
                    <i class='{{ $icone }} card-icon'></i> 
                </div>
            </a>
        @endforeach
    </div>

    <div class='faqall'>
        <div class="faq-container w-correct">
        <div class="faq">
            <h2>Foire aux questions</h2>
            @foreach($contents->where('section', 'faq')->sortBy('ordre') as $faq)
                @php 
                    [$question, $reponse] = explode('|', $faq->value);
                @endphp
                <details class="faq-item">
                    <summary class="faq-question">
                        {{ $question }}
                        <i class='bx bx-chevron-down faq-icon'></i>
                    </summary>
                    <div class="faq-answer">
                        <p>{{ $reponse }}</p>
                    </div>
                </details>
            @endforeach
        </div>
        <img src="{{ asset('img/faq.jpg') }}" alt="Illustration FAQ" class="faq-illustration">
        
    </div></div>


    <div class="contact-container">
    <div class="contact-left">
        <div class="illustration-wrapper">
            <img src="{{ asset('img/jemelance.png') }}" alt="Je me lance">
            <div class="cta-overlay">
                <h2>{!! nl2br(e($contents->firstWhere('key', 'left_title')->value ?? 'JE ME LANCE !')) !!}</h2>
                <a class="btn-primary" href="">En savoir plus</a>
            </div>
        </div>
    </div>

    <div class="contact-right">
        <h3>{{ $contents->firstWhere('key', 'form_title')->value ?? 'Contacter Tedy Florette' }}</h3>
        
        <form action="" method="POST" class="contact-form">
            @csrf
            <div class="form-group">
                <label for="email">ADRESSE MAIL*</label>
                <input type="email" id="email" name="email" required maxlength="32">
                <span class="char-count">0/32</span>
            </div>

            <div class="form-group">
                <label for="message">VOTRE MESSAGE*</label>
                <textarea id="message" name="message" required maxlength="185" placeholder="Lorem ipsum"></textarea>
                <span class="char-count">0/185</span>
            </div>

            <button type="submit" class="btn-submit">Envoyer</button>
        </form>
    </div></div>

   

    <div class="avis-section w-correct">
        <h2>Ce que les élèves pensent de la formation</h2>
        <p class="avis-subtitle">porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...</p>

        <div class="avis-container">
            @foreach($contents->where('section', 'avis-p')->sortBy('ordre') as $item)
                @php 
                    // On découpe proprement et on s'assure d'avoir 3 éléments
                    $parts = explode('|', $item->value);
                    $nom = $parts[0] ?? 'Élève anonyme';
                    $commentaire = $parts[1] ?? 'Pas de commentaire.';
                    $note = $parts[2] ?? 5;
                @endphp
                
                <div class="avis-card">
                    <div class="avis-header">
                        <h3>{{ $nom }}</h3>
                        <div class="avis-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bx {{ $i <= $note ? 'bxs-star' : 'bx-star' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="avis-text">“ {{ $commentaire }} ”</p>
                </div>
            @endforeach
        </div>
    </div>


@endsection

