@extends('layouts.app')

@section('title', 'Plan du site')
@section('meta_description', 'Plan du site TedyCRPE : accédez rapidement à toutes les pages.')

@section('content')
    <section class="legal-section">
        <div class="legal-container">
            <h1>Plan du site</h1>

            <ul class="sitemap-list">
                <li><a href="{{ route('home') }}">Accueil</a></li>
                <li><a href="{{ route('crpe') }}">Formation CRPE</a></li>
                <li><a href="{{ route('agenda.index') }}">Agenda</a></li>
                <li><a href="{{ route('qui-suis-je') }}">Qui suis-je ?</a></li>
                <li><a href="{{ route('home') }}#contact">Contact</a></li>
                <li><a href="{{ route('formation.index') }}">Mon compte</a></li>
                <li>
                    Informations légales
                    <ul>
                        <li><a href="{{ route('mentions-legales') }}">Mentions légales</a></li>
                        <li><a href="{{ route('donnees-personnelles') }}">Données personnelles</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </section>
@endsection
