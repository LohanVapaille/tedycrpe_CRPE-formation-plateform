<?php // app/Http/Controllers/Admin/AdminBlocController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bloc;
use Illuminate\Http\Request;

class AdminBlocController extends Controller
{
    public function index()
    {
        $blocs = Bloc::withCount('matieres')->orderBy('ordre')->orderBy('id')->get();

        return view('admin.blocs.index', compact('blocs'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50']);

        // Nouveau bloc placé en dernier
        Bloc::create([
            'name' => $request->name,
            'ordre' => (Bloc::max('ordre') ?? 0) + 1,
        ]);

        return redirect()->route('admin.blocs')->with('success', 'Bloc créé.');
    }

    public function update(Request $request, Bloc $bloc)
    {
        $request->validate(['name' => 'required|string|max:50']);

        $bloc->update(['name' => $request->name]);

        return redirect()->route('admin.blocs')->with('success', 'Bloc renommé.');
    }

    public function destroy(Bloc $bloc)
    {
        // Les modules du bloc ne sont pas supprimés : ils passent dans "Autres".
        // Fait à la main : les tables sont en MyISAM, la clé étrangère (nullOnDelete) n'est pas appliquée.
        $bloc->matieres()->update(['bloc_id' => null]);
        $bloc->delete();

        return redirect()->route('admin.blocs')->with('success', 'Bloc supprimé.');
    }

    // Glisser-déposer : reçoit les ids des blocs dans le nouvel ordre
    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:blocs,id',
        ]);

        foreach ($request->ids as $index => $id) {
            Bloc::where('id', $id)->update(['ordre' => $index + 1]);
        }

        return response()->json(['status' => 'ok']);
    }
}
