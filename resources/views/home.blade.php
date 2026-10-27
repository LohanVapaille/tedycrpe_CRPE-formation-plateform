@extends('layouts.app')

@section('title', 'Accueil')

@section(
    'meta_description',
    'Préparez le CRPE avec Tedy Florette grâce à des cours, des ressources et un accompagnement personnalisé.'
)

@section('content')
    <div class="welcome-section ">
        <div class="w-correct">
            <h1>{{ $contents->firstWhere('key', 'title')->value ?? 'Texte de présentation par défaut.' }}</h1>

            <a class="welcome-btn btn-primary" target="_blank"
                href="https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ2QBMwbgmWglOJHrCIE-S12yVzNahg0GGqPIl1MA4xwXlgmRzXVDucuTl8lkXu7zH_U0ekXkyAR">Réserver
                un appel découverte</a>
        </div>
    </div>



    <div class="presentation-tedy w-correct">
        <div class="presentation-text">
            <h2>Tedy Florette, formateur CRPE</h2>
            <p>{{ $contents->firstWhere('key', 'subtitle')->value ?? 'Texte de présentation par défaut.' }}</p>
            <a class="btn-primary" href="{{ route('qui-suis-je') }}">En savoir plus</a>
        </div>
        <div class="presentation-image">
            <img src="{{ $contents->firstWhere('key', 'photo-tedy')->value ?? 'img/photo/phototedy.png' }}"
                alt="Tedy Florette">
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
    {{-- SECTION BANNIÈRE CTA APPEL DÉCOUVERTE --}}
    <div class="discovery-banner-bg">
        <div class="crpe-container w-correct discovery-banner-flex">
            <div class="discovery-banner-content">
                <h2>Envie de faire décoller votre préparation au CRPE ?</h2>
                <p>Discutons ensemble de vos objectifs lors d'un <strong>appel découverte 100% gratuit</strong> et sans
                    engagement.</p>
            </div>
            <div class="discovery-banner-action">
                <a href="{{ url('/visio') }}" class="crpe-btn btn-teal-light">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="btn-icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                    </svg>
                    Réserver mon créneau
                </a>
            </div>
        </div>
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
                    <i class='{{ $icone }} bx card-icon'></i>
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

        </div>
    </div>


    <div class="contact-container" id=contact>
        <div class="contact-left">
            <div class="illustration-wrapper">
                <img src="{{ asset('img/jemelance.png') }}" alt="Je me lance">
                <div class="cta-overlay">
                    <h2>{!! nl2br(e($contents->firstWhere('key', 'left_title')->value ?? 'JE ME LANCE !')) !!}</h2>
                    <a class="btn-primary" target="_blank"
                        href="https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ2QBMwbgmWglOJHrCIE-S12yVzNahg0GGqPIl1MA4xwXlgmRzXVDucuTl8lkXu7zH_U0ekXkyAR">En
                        savoir plus</a>
                </div>
            </div>
        </div>

        <div class="contact-right">
            <h3>{{ $contents->firstWhere('key', 'form_title')->value ?? 'Contacter Tedy Florette' }}</h3>

            {{-- Messages Flash --}}
            @if(session('success'))
                <div style="color: #2e7d32; padding: 10px; background: #e8f5e9; border-radius: 8px; margin-bottom: 15px;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                @csrf


                <div class="form-group">
                    <label for="email">ADRESSE MAIL*</label>
                    <input type="email" id="email" name="email" required maxlength="32" value="{{ old('email') }}">
                    @error('email') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                    <span class="char-count">0/32</span>
                </div>

                <div class="form-group">
                    <label for="message">VOTRE MESSAGE*</label>
                    <textarea id="message" name="message" required maxlength="185"
                        placeholder="Votre message ici...">{{ old('message') }}</textarea>
                    @error('message') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                    <span class="char-count">0/185</span>
                </div>

                {{-- Champ piège Honeypot invisible pour les humains --}}
                <div style="position: absolute; left: -9999px; top: -9999px;">
                    <input type="text" name="fax_number" id="fax_number" tabindex="-1" autocomplete="off">
                </div>

                <button type="submit" class="btn-submit">Envoyer</button>
            </form>
        </div>
    </div>



    <div class="avis-section w-correct">
        <h2>{{ $contents->firstWhere('key', 'titre_avis')->value ?? 'Ce que les élèves pensent de la formation' }}</h2>
        @if(filled($contents->firstWhere('key', 'phrase_avis')->value ?? null))
            <p class="avis-subtitle">{{ $contents->firstWhere('key', 'phrase_avis')->value }}</p>
        @endif

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

        <div style="display:flex "><a class="lirelesautreavis btn-primary " target="_blank"
                href="https://www.google.com/search?client=opera-gx&hs=vJ1&sa=X&sca_esv=9e14c540528a302f&q=avis%20sur%20tedy%20florette%20ei%20moissy-cramayel&rflfq=1&num=20&stick=H4sIAAAAAAAAAONgkxIxNDQyMjMzszS0MDIyMLQ0MDI2MNvAyPiKUTOxLLNYobi0SKEkNaVSIS0nvyi1pCRVITVTITc_s7i4Uje5KDE3sTI1ZxEr8WoBVUy4tHMAAAA&rldimm=11226669182201902306&tbm=lcl&hl=fr-FR&ved=0CAYQ5foLahcKEwjgme3utK-VAxUAAAAAHQAAAAAQCw&biw=1912&bih=948&dpr=1#lkt=LocalPoiReviews&arid=Ci9DQUlRQUNvZENodHljRjlvT2pFNGFWWjVNakIzWTB0d1ZXOVdRV05QVldGdVZFRRAB">Lire
                tous les avis</a>
        </div>






@endsection