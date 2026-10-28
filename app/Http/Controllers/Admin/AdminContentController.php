<?php // app/Http/Controllers/Admin/AdminContentController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;

class AdminContentController extends Controller
{
    public function index()
    {
        $contents = Content::orderBy('page')
            ->orderBy('section')
            ->orderBy('ordre')
            ->get()
            ->groupBy('page');

        return view('admin.contenu.index', compact('contents'));
    }

    // AdminContentController.php
    public function update(Request $request, Content $content)
    {
        if ($request->hasFile('image')) {
            $request->validate(['image' => 'required|image|max:2048']);
            $filename = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('img/upload'), $filename);
            $content->update(['value' => 'img/upload/' . $filename]);

        } elseif ($request->has('parts') && is_array($request->parts)) {
            $parts = array_map(fn($p) => trim((string) $p), $request->parts);

            // Parties 2, 3... toutes vides : on enregistre un texte simple, sans "|" final
            // (sinon le "|" s'affiche sur le site, ex. titre de l'accueil)
            $suivantesVides = count(array_filter(array_slice($parts, 1), fn($p) => $p !== '')) === 0;
            $value = $suivantesVides ? $parts[0] : implode('|', $parts);

            $content->update(['value' => $value]);

        } else {
            $request->validate(['value' => 'required|string']);
            $content->update(['value' => $request->value]);
        }

        return back()->with('success', 'Contenu mis à jour.');
    }
}