<?php // app/Http/Controllers/Admin/AdminFormationController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matiere;
use App\Models\Cours;
use Illuminate\Http\Request;

class AdminFormationController extends Controller
{
    // AdminFormationController.php
    public function index()
    {
        $matieres = Matiere::with('cours')
            ->orderBy('module_associe', 'desc') // CRPE BAC + 5 avant CRPE BAC + 3
            ->orderBy('ordre')
            ->get()
            ->groupBy('module_associe');

        return view('admin.formations.index', compact('matieres'));
    }

    public function create()
    {
        return view('admin.formations.form', ['matiere' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'module_associe' => 'required|string|max:100',
            'ordre' => 'required|integer',
        ]);

        Matiere::create($request->only('name', 'module_associe', 'ordre'));
        return redirect()->route('admin.formations')->with('success', 'Module créé.');
    }

    public function edit(Matiere $matiere)
    {
        return view('admin.formations.form', compact('matiere'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'module_associe' => 'required|string|max:100',
            'ordre' => 'required|integer',
        ]);

        $matiere->update($request->only('name', 'module_associe', 'ordre'));
        return redirect()->route('admin.formations')->with('success', 'Module mis à jour.');
    }

    public function destroy(Matiere $matiere)
    {
        $matiere->delete();
        return redirect()->route('admin.formations')->with('success', 'Module supprimé.');
    }
}