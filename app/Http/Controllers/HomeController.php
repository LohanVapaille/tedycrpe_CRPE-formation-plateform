<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\Content;
use App\Models\Cours;
use App\Models\Chat;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Mettre à jour la méthode index pour l'accueil
    public function index()
    {
        $matieres = Matiere::with('cours')->orderBy('ordre')->get();
        // On récupère les textes de l'accueil sous forme de clé => valeur
        $texts = Content::where('page', 'home')->get()->pluck('value', 'key');
        $chat = Chat::find(1);
        return view('home', compact('matieres', 'texts'));
    }

    // Ajouter la méthode pour "Qui suis-je ?"
    public function about()
    {
        $texts = Content::where('page', 'about')->get()->pluck('value', 'key');
        $chat = Chat::find(1);
        return view('about', compact('texts', 'chat'));
    }
}