<?php // app/Http/Controllers/Admin/AdminUserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', ['user' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:60',
            'name' => 'required|string|max:60',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:2',
            'access' => 'required|string|max:20',
            'is_tutor' => 'boolean',
            'academie' => 'nullable|string|max:60',
        ]);

        User::create([
            'firstname' => $request->firstname,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'access' => $request->access,
            'is_tutor' => $request->boolean('is_tutor'),
            'academie' => $request->academie,
        ]);

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'firstname' => 'required|string|max:60',
            'name' => 'required|string|max:60',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'access' => 'required|string|max:20',
            'is_tutor' => 'boolean',
            'academie' => 'nullable|string|max:60',
        ]);

        $user->update([
            'firstname' => $request->firstname,
            'name' => $request->name,
            'email' => $request->email,
            'access' => $request->access,
            'is_tutor' => $request->boolean('is_tutor'),
            'academie' => $request->academie,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé.');
    }
}