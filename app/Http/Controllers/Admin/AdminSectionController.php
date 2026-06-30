<?php // app/Http/Controllers/Admin/AdminSectionController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\SectionCours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSectionController extends Controller
{
    public function index(Cours $cours)
    {
        $sections = $cours->sections()->orderBy('ordre')->get();
        return view('admin.sections.index', compact('cours', 'sections'));
    }

    public function store(Request $request, Cours $cours)
    {
        // 1. Validation de base + validation conditionnelle pour l'image
        $request->validate([
            'type' => 'required|in:titre,txt,img,video,upload',
            'titre' => 'nullable|string|max:255',
            'contenu' => 'nullable|string',
            'image' => 'required_if:type,img|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // Obligatoire si type = img
            'ordre' => 'required|integer',
        ]);

        // 2. Préparation des données
        $data = $request->only('type', 'titre', 'contenu', 'ordre');

        if ($request->type === 'titre') {
            $data['contenu'] = null; // Forcer à null pour un titre
        } elseif ($request->type === 'img' && $request->hasFile('image')) {
            // Stockage de l'image dans storage/app/public/sections
            $path = $request->file('image')->store('sections', 'public');
            $data['contenu'] = $path; // On enregistre le chemin dans le champ contenu
        }

        // 3. Création
        $cours->sections()->create($data);

        return redirect()->route('admin.sections', $cours)->with('success', 'Section ajoutée.');
    }


    public function update(Request $request, SectionCours $section)
    {
        // 1. Validation (l'image est optionnelle lors d'une modification)
        $request->validate([
            'type' => 'required|in:titre,txt,img,video,upload',
            'titre' => 'nullable|string|max:255',
            'contenu' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'ordre' => 'required|integer',
        ]);

        $data = $request->only('type', 'titre', 'contenu', 'ordre');

        if ($request->type === 'titre') {
            $data['contenu'] = null;
        } elseif ($request->type === 'img') {
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($section->contenu && Storage::disk('public')->exists($section->contenu)) {
                    Storage::disk('public')->delete($section->contents);
                }
                // Stocker la nouvelle
                $path = $request->file('image')->store('sections', 'public');
                $data['contenu'] = $path;
            } else {
                // Si le type passe à image mais qu'on n'upload rien, on garde l'ancienne valeur stockée
                $data['contenu'] = $section->contenu;
            }
        }

        $section->update($data);

        return back()->with('success', 'Section mise à jour.');
    }



    public function destroy(SectionCours $section)
    {
        $cours = $section->cours;

        // Supprimer le fichier image du stockage si la section était une image
        if ($section->type === 'img' && $section->contenu && Storage::disk('public')->exists($section->contenu)) {
            Storage::disk('public')->delete($section->contenu);
        }

        $section->delete();

        return redirect()->route('admin.sections', $cours)->with('success', 'Section supprimée.');
    }

    public function reorder(Request $request)
    {
        foreach ($request->ordre as $item) {
            SectionCours::where('id', $item['id'])->update(['ordre' => $item['ordre']]);
        }
        return response()->json(['status' => 'ok']);
    }
}