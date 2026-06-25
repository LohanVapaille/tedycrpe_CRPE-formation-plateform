<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Support\Facades\Auth;

class FormationController extends Controller
{
    public function index()
    {
        // On récupère les matières pour l'étudiant connecté
        $matieres = Matiere::with('cours')->orderBy('ordre')->get();

        return view('formation.index', compact('matieres'));
    }
}