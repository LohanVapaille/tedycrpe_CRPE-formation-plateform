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
        // Dans ton contrôleur : enlève le pluck
        $contents = Content::where('page', 'home')->get();
        $chat = Chat::find(1);
        return view('home', compact('matieres', 'contents', 'chat'));
    }

    // Ajouter la méthode pour "Qui suis-je ?"
    public function about()
    {
        $contents = Content::where('page', 'about')->get()->pluck('value', 'key');
        $chat = Chat::find(1);
        return view('about', compact('contents', 'chat'));
    }

    public function crpe()
    {
        // On récupère le contenu de la BDD dédié à cette page
        $contents = Content::where('page', 'crpe')->get();

        return view('crpe', compact('contents'));
    }
}