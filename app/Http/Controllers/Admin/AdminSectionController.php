<?php // app/Http/Controllers/Admin/AdminSectionController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\SectionCours;
use Illuminate\Http\Request;

class AdminSectionController extends Controller
{
    public function index(Cours $cours)
    {
        $sections = $cours->sections()->orderBy('ordre')->get();
        return view('admin.sections.index', compact('cours', 'sections'));
    }

    public function store(Request $request, Cours $cours)
    {
        $request->validate([
            'type' => 'required|in:titre,txt,img,video,upload',
            'titre' => 'nullable|string',
            'contenu' => 'nullable|string',
            'ordre' => 'required|integer',
        ]);

        $cours->sections()->create($request->only('type', 'titre', 'contenu', 'ordre'));
        return redirect()->route('admin.sections', $cours)->with('success', 'Section ajoutée.');
    }

    public function update(Request $request, SectionCours $section)
    {
        $request->validate([
            'type' => 'required|in:titre,txt,img,video,upload',
            'titre' => 'nullable|string',
            'contenu' => 'nullable|string',
            'ordre' => 'required|integer',
        ]);

        $section->update($request->only('type', 'titre', 'contenu', 'ordre'));
        return back()->with('success', 'Section mise à jour.');
    }

    public function destroy(SectionCours $section)
    {
        $cours = $section->cours;
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