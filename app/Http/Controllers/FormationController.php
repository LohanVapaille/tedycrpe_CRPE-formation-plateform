<?php

namespace App\Http\Controllers;

class FormationController extends Controller
{
    public function index()
    {
        // Seuls les utilisateurs connectés arriveront ici
        return view('formation.index');
    }
}