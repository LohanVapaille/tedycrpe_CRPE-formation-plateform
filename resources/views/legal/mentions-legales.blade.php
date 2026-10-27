@extends('layouts.app')

@section('title', 'Mentions légales')
@section('meta_description', 'Mentions légales du site TedyCRPE : éditeur, hébergeur et propriété intellectuelle.')

@section('content')
    <section class="legal-section">
        <div class="legal-container">
            <h1>Mentions légales</h1>

            <p>
                Conformément à la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique, voici les
                informations relatives à l'éditeur et à l'hébergeur du site coachcrpeflorettetedy.com.
            </p>

            <h2>Éditeur du site</h2>
            <p>
                Tedy Florette, Coach CRPE<br>
                Entrepreneur individuel (micro-entreprise)<br>
                117, rue de Séville, 77550 Moissy-Cramayel, France<br>
                SIRET : 103 384 541 00019<br>
                Téléphone : <a href="tel:+33680886033">+33 6 80 88 60 33</a><br>
                E-mail : <a href="mailto:tflorette1408@gmail.com">tflorette1408@gmail.com</a><br>
                TVA non applicable, article 293 B du Code général des impôts.
            </p>

            <h2>Directeur de la publication</h2>
            <p>Tedy Florette</p>

            <h2>Hébergeur</h2>
            <p>
                OVH SAS (OVHcloud)<br>
                2, rue Kellermann, 59100 Roubaix, France<br>
                Téléphone : 1007<br>
                <a href="https://www.ovhcloud.com" target="_blank" rel="noopener">www.ovhcloud.com</a>
            </p>

            <h2>Conception et développement</h2>
            <p>
                <a href="https://lohanvapaille.fr" target="_blank" rel="noopener">Lohan Vapaille</a>,
                <a href="https://lovaweb.lohanvapaille.fr" target="_blank" rel="noopener">Lovaweb</a>
            </p>

            <h2>Propriété intellectuelle</h2>
            <p>
                L'ensemble des contenus de ce site et de l'espace de formation (textes, fiches, quiz, corrigés, vidéos,
                podcasts, cartes mentales, flashcards, logo, images) est la propriété de Tedy Florette, sauf mention
                contraire. Ils sont protégés par le Code de la propriété intellectuelle.
            </p>
            <p>
                Les ressources de l'espace de formation sont réservées à l'usage personnel des élèves inscrits. Toute
                reproduction, diffusion ou revente, totale ou partielle, sans autorisation écrite préalable est interdite.
            </p>

            <h2>Responsabilité</h2>
            <p>
                Tedy Florette s'efforce de fournir des informations exactes et à jour, notamment sur les programmes et les
                épreuves du CRPE. Il ne peut toutefois garantir l'absence d'erreur et invite les candidats à se référer aux
                textes officiels publiés par le ministère de l'Éducation nationale.
            </p>
            <p>
                Le site peut contenir des liens vers d'autres sites. Tedy Florette n'est pas responsable de leur contenu.
            </p>

            <h2>Contact</h2>
            <p>
                Pour toute question sur le site, écrivez à
                <a href="mailto:tflorette1408@gmail.com">tflorette1408@gmail.com</a> ou utilisez le
                <a href="{{ route('home') }}#contact">formulaire de contact</a> de la page d'accueil.
            </p>
        </div>
    </section>
@endsection
