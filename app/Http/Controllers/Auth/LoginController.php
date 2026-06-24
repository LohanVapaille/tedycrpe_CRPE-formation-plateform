<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 1. Récupérer l'utilisateur par son email avant de l'authentifier complètement
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // 2. Si le mot de passe entré est "0000", on le redirige vers une page de changement forcé
            if ($request->password === '0000') {
                // On le connecte temporairement ou on stocke son ID en session
                session(['force_password_user_id' => $user->id]);
                return redirect()->route('auth.password.force');
            }

            // 3. Sinon, connexion normale
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('formation.index');
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function showForcePasswordForm()
    {
        if (!session()->has('force_password_user_id'))
            return redirect()->route('login');
        return view('auth.force-password');
    }

    public function updateForcePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $userId = session('force_password_user_id');
        $user = \App\Models\User::findOrFail($userId);

        // Sauvegarde du nouveau mot de passe
        $user->password = Hash::make($request->password);
        $user->save();

        // Connexion officielle de l'utilisateur
        Auth::login($user);
        session()->forget('force_password_user_id');

        return redirect()->route('formation.index');
    }
}