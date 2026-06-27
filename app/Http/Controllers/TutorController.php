<?php
// app/Http/Controllers/TutorController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class TutorController extends Controller
{
    public function index()
    {
        abort_if(!Auth::user()->is_tutor, 403);

        // Récupère tous les élèves (non tuteurs) avec leur chat
        $eleves = User::where('is_tutor', 0)
            ->with('chats') // relation hasOne Chat sur id_user
            ->get();

        return view('tutor.backoffice', compact('eleves'));
    }
}