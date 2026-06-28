<?php // app/Http/Controllers/Admin/AdminCoursController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Matiere;
use Illuminate\Http\Request;

class AdminCoursController extends Controller
{
    // AdminCoursController.php
    public function index()
    {
        $cours = Cours::with('matiere')
            ->orderBy('id_matiere')
            ->orderBy('ordre')
            ->get()
            ->groupBy(fn($c) => $c->matiere->module_associe . '|||' . $c->matiere->name);

        return view('admin.cours.index', compact('cours'));
    }

    public function create()
    {
        $matieres = Matiere::orderBy('ordre')->get();
        return view('admin.cours.form', ['cours' => null, 'matieres' => $matieres]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'ordre' => 'required|integer',
            'id_matiere' => 'required|exists:matieres,id',
        ]);

        Cours::create($request->only('name', 'ordre', 'id_matiere'));
        return redirect()->route('admin.cours')->with('success', 'Cours créé.');
    }

    public function edit(Cours $cours)
    {
        $matieres = Matiere::orderBy('ordre')->get();
        return view('admin.cours.form', compact('cours', 'matieres'));
    }

    public function update(Request $request, Cours $cours)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'ordre' => 'required|integer',
            'id_matiere' => 'required|exists:matieres,id',
        ]);

        $cours->update($request->only('name', 'ordre', 'id_matiere'));
        return redirect()->route('admin.cours')->with('success', 'Cours mis à jour.');
    }

    public function destroy(Cours $cours)
    {
        $cours->delete();
        return redirect()->route('admin.cours')->with('success', 'Cours supprimé.');
    }
}