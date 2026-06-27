<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\MessageController; // N'oublie pas d'importer le MessageController

// Appelle la méthode index du HomeController pour la page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/qui-suis-je', [HomeController::class, 'about'])->name('about');
Route::get('/crpe', [HomeController::class, 'crpe'])->name('crpe');
// Good
// Vérifie bien que ta route GET ressemble à ça (au singulier ou pluriel, mais identique partout) :
Route::get('/formation', [FormationController::class, 'index'])->name('formation.index');

// Invités (Public)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Passage forcé pour le mot de passe initial
Route::get('/configuration-password', [LoginController::class, 'showForcePasswordForm'])->name('auth.password.force');
Route::post('/configuration-password', [LoginController::class, 'updateForcePassword'])->name('auth.password.update');

// Espace Connecté Privé
Route::middleware(['auth'])->group(function () {
    Route::get('/formation', [FormationController::class, 'index'])->name('formation.index');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // NOUVELLE ROUTE : Affichage d'un cours spécifique
    Route::get('/formation/cours/{id}', [FormationController::class, 'showRoute'])->name('formation.cours');
    Route::post('/formation/cours/{id}/terminer', [FormationController::class, 'terminerCours'])->name('formation.cours.terminer');
    Route::post('/chat/store', [ChatController::class, 'store'])->name('chat.store');
});
