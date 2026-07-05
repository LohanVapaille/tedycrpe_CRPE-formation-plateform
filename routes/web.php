<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TutorController;
use Illuminate\Support\Facades\Route;

use Spatie\Sitemap\SitemapGenerator;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DevoirController;
use App\Http\Controllers\ContactController;


use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminFormationController;
use App\Http\Controllers\Admin\AdminCoursController;
use App\Http\Controllers\Admin\AdminSectionController;
use App\Http\Controllers\Admin\AdminContentController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminDevoirController;


// Appelle la méthode index du HomeController pour la page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/qui-suis-je', [HomeController::class, 'about'])->name('about');
Route::get('/crpe', [HomeController::class, 'crpe'])->name('crpe');
// Good
// Vérifie bien que ta route GET ressemble à ça (au singulier ou pluriel, mais identique partout) :
Route::get('/formation', [FormationController::class, 'index'])->name('formation.index');
// Route publique de traitement du formulaire
// Route existante
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Route de secours : si on arrive sur /contact en GET, on redirige vers l'accueil (ou là où est ton formulaire)
Route::get('/contact', function () {
    return redirect()->route('home'); // Remplace 'home' par le nom de ta route d'accueil si nécessaire
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::get('/qui-suis-je', [HomeController::class, 'quiSuisJe'])->name('qui-suis-je');

Route::get('/visio', function () {
    return redirect()->away('https://calendar.google.com/calendar/u/0/appointments/schedules/AcZssZ2QBMwbgmWglOJHrCIE-S12yVzNahg0GGqPIl1MA4xwXlgmRzXVDucuTl8lkXu7zH_U0ekXkyAR');
})->name('visio');

Route::post('/devoirs/store/{section}', [DevoirController::class, 'store'])->name('devoirs.store');

// Passage forcé pour le mot de passe initial
Route::get('/configuration-password', [LoginController::class, 'showForcePasswordForm'])->name('auth.password.force');
Route::post('/configuration-password', [LoginController::class, 'updateForcePassword'])->name('auth.password.update');

// Route::get('/generate-sitemap', function () {
//     SitemapGenerator::create('https://coachcrpeflorettetedy.com')
//         ->writeToFile(public_path('sitemap.xml'));

//     return 'Sitemap généré !';
// });

// Espace Connecté Privé
Route::middleware(['auth'])->group(function () {
    Route::get('/formation', [FormationController::class, 'index'])->name('formation.index');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // NOUVELLE ROUTE : Affichage d'un cours spécifique
    Route::get('/formation/cours/{id}', [FormationController::class, 'showRoute'])->name('formation.cours');
    Route::post('/formation/cours/{id}/terminer', [FormationController::class, 'terminerCours'])->name('formation.cours.terminer');
    Route::post('/chat/store', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/tuteur/chats', [ChatController::class, 'tutorChannels'])->name('chat.tutorChannels');
    Route::get('/chat/{chat}/messages', [ChatController::class, 'history'])->name('chat.history');
    Route::get('/admin/devoirs', [AdminDevoirController::class, 'index'])->name('admin.devoirs.index');
    Route::get('/admin/devoirs/{rendu}/download', [AdminDevoirController::class, 'download'])->name('admin.devoirs.download');




});

// routes/web.php
Route::prefix('admin')->middleware(['auth', 'tutor'])->group(function () {

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // Formations (matieres)
    Route::get('/formations', [AdminFormationController::class, 'index'])->name('admin.formations');
    Route::get('/formations/create', [AdminFormationController::class, 'create'])->name('admin.formations.create');
    Route::post('/formations', [AdminFormationController::class, 'store'])->name('admin.formations.store');
    Route::get('/formations/{matiere}/edit', [AdminFormationController::class, 'edit'])->name('admin.formations.edit');
    Route::put('/formations/{matiere}', [AdminFormationController::class, 'update'])->name('admin.formations.update');
    Route::delete('/formations/{matiere}', [AdminFormationController::class, 'destroy'])->name('admin.formations.destroy');

    // Cours
    Route::get('/cours', [AdminCoursController::class, 'index'])->name('admin.cours');
    Route::get('/cours/create', [AdminCoursController::class, 'create'])->name('admin.cours.create');
    Route::post('/cours', [AdminCoursController::class, 'store'])->name('admin.cours.store');
    Route::get('/cours/{cours}/edit', [AdminCoursController::class, 'edit'])->name('admin.cours.edit');
    Route::put('/cours/{cours}', [AdminCoursController::class, 'update'])->name('admin.cours.update');
    Route::delete('/cours/{cours}', [AdminCoursController::class, 'destroy'])->name('admin.cours.destroy');

    Route::get('/messages', [AdminMessageController::class, 'index'])->name('admin.messages.index');
    Route::patch('/messages/{contact_message}/toggle', [AdminMessageController::class, 'toggleRead'])->name('admin.messages.toggle');
    Route::delete('/messages/{contact_message}', [AdminMessageController::class, 'destroy'])->name('admin.messages.destroy');

    // Sections d'un cours
    Route::get('/cours/{cours}/sections', [AdminSectionController::class, 'index'])->name('admin.sections');
    Route::post('/cours/{cours}/sections', [AdminSectionController::class, 'store'])->name('admin.sections.store');
    Route::put('/sections/{section}', [AdminSectionController::class, 'update'])->name('admin.sections.update');
    Route::delete('/sections/{section}', [AdminSectionController::class, 'destroy'])->name('admin.sections.destroy');
    Route::post('/sections/reorder', [AdminSectionController::class, 'reorder'])->name('admin.sections.reorder');

    Route::get('/admin/contenu', [AdminContentController::class, 'index'])->name('admin.contenu');
    Route::put('/admin/contenu/{content}', [AdminContentController::class, 'update'])->name('admin.contenu.update');

    Route::prefix('admin')->middleware(['auth', 'tutor'])->group(function () {
        // ... routes existantes ...
        Route::get('/evenements', [AdminEventController::class, 'index'])->name('admin.evenements');
        Route::get('/evenements/create', [AdminEventController::class, 'create'])->name('admin.evenements.create');
        Route::post('/evenements', [AdminEventController::class, 'store'])->name('admin.evenements.store');
        Route::get('/evenements/{event}/edit', [AdminEventController::class, 'edit'])->name('admin.evenements.edit');
        Route::put('/evenements/{event}', [AdminEventController::class, 'update'])->name('admin.evenements.update');
        Route::delete('/evenements/{event}', [AdminEventController::class, 'destroy'])->name('admin.evenements.destroy');
    });
});
