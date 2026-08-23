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
        // 1. Validation : ajout de 'fichier' dans les types autorisés et assouplissement de la règle du fichier
        $request->validate([
            'type' => 'required|in:titre,txt,img,video,zonedepot,fichier',
            'titre' => 'nullable|string|max:255',
            'contenu' => 'nullable|string',
            'image' => 'required_if:type,img,fichier|file|max:10240', // Accepte tout type de fichier jusqu'à 10 Mo
            'ordre' => 'required|integer',
        ]);

        // 2. Préparation des données
        $data = $request->only('type', 'titre', 'contenu', 'ordre');

        if ($request->type === 'titre') {
            $data['contenu'] = null;
        } elseif (($request->type === 'img' || $request->type === 'fichier') && $request->hasFile('image')) {
            // Stockage de l'image ou du document téléchargeable dans storage/app/public/sections
            $path = $request->file('image')->store('sections', 'public');
            $data['contenu'] = $path;
        }

        // 3. Création
        $cours->sections()->create($data);

        return redirect()->route('admin.sections', $cours)->with('success', 'Section ajoutée.');
    }

    public function update(Request $request, SectionCours $section)
    {
        // 1. Validation
        $request->validate([
            'type' => 'required|in:titre,txt,img,video,zonedepot,fichier',
            'titre' => 'nullable|string|max:255',
            'contenu' => 'nullable|string',
            'image' => 'nullable|file|max:10240',
            'ordre' => 'required|integer',
        ]);

        $data = $request->only('type', 'titre', 'contenu', 'ordre');

        if ($request->type === 'titre') {
            $data['contenu'] = null;
        } elseif ($request->type === 'img' || $request->type === 'fichier') {
            if ($request->hasFile('image')) {
                // Supprimer l'ancien fichier s'il existe
                if ($section->contenu && Storage::disk('public')->exists($section->contenu)) {
                    Storage::disk('public')->delete($section->contenu);
                }
                // Stocker le nouveau
                $path = $request->file('image')->store('sections', 'public');
                $data['contenu'] = $path;
            } else {
                // Si on ne change pas de fichier, on conserve l'ancien chemin
                $data['contenu'] = $section->contenu;
            }
        }

        $section->update($data);

        return back()->with('success', 'Section mise à jour.');
    }

    public function destroy(SectionCours $section)
    {
        $cours = $section->cours;

        // Supprimer le fichier du stockage si la section était une image ou un fichier
        if (in_array($section->type, ['img', 'fichier']) && $section->contenu && Storage::disk('public')->exists($section->contenu)) {
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