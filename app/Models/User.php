<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany as EloquentHasMany;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'firstname',
        'name',
        'email',
        'password',
        'access',
        'is_tutor',
        'academie',
    ];

    /**
     * Accès "e-learning" : mêmes cours que le module de base, sans le groupe WhatsApp.
     */
    public const ACCES_ELEARNING = [
        'CRPE BAC + 5 E-learning' => 'CRPE BAC + 5',
        'CRPE BAC + 3 E-learning' => 'CRPE BAC + 3',
    ];

    /**
     * Accès "3ème voie/Interne" : uniquement les modules cochés comme tels dans l'admin
     * (oral de leçon Bac +5 et oral 2 Bac +3), sur les deux niveaux.
     */
    public const ACCES_TROISIEME_VOIE = '3ème voie/Interne';

    /**
     * Toutes les valeurs possibles du champ "access" (libellé affiché dans l'admin).
     */
    public const ACCES = [
        'all' => 'Tous les cours',
        'CRPE BAC + 5' => 'CRPE BAC +5',
        'CRPE BAC + 5 E-learning' => 'CRPE BAC +5 E-learning',
        'CRPE BAC + 3' => 'CRPE BAC +3',
        'CRPE BAC + 3 E-learning' => 'CRPE BAC +3 E-learning',
        self::ACCES_TROISIEME_VOIE => '3ème voie/Interne',
    ];

    /**
     * Module de cours auquel l'utilisateur a accès ("all", "CRPE BAC + 5" ou "CRPE BAC + 3").
     */
    public function module(): string
    {
        return self::ACCES_ELEARNING[$this->access] ?? $this->access;
    }

    public function isElearning(): bool
    {
        return array_key_exists($this->access, self::ACCES_ELEARNING);
    }

    public function isTroisiemeVoie(): bool
    {
        return $this->access === self::ACCES_TROISIEME_VOIE;
    }

    /**
     * Les cours de l'utilisateur couvrent-ils les deux niveaux (affichage groupé Bac +5 / Bac +3) ?
     */
    public function voitPlusieursNiveaux(): bool
    {
        return $this->module() === 'all' || $this->isTroisiemeVoie();
    }

    /**
     * L'utilisateur a-t-il accès à ce module ?
     */
    public function aAccesA(Matiere $matiere): bool
    {
        return match (true) {
            $this->module() === 'all' => true,
            $this->isTroisiemeVoie() => $matiere->acces_troisieme_voie,
            default => $matiere->module_associe === $this->module(),
        };
    }

    /**
     * Les attributs qui doivent être masqués pour la sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Un utilisateur peut ouvrir plusieurs chats de support / discussion.
     */
    public function chats(): EloquentHasMany
    {
        return $this->hasMany(Chat::class, 'id_user');
    }

    public function rendus()
    {
        return $this->hasMany(RenduDevoir::class, 'user_id');
    }

    /**
     * Un utilisateur peut écrire plusieurs messages.
     */
    public function messages(): EloquentHasMany
    {
        return $this->hasMany(Message::class, 'id_writer');
    }

    public function coursTermines()
    {
        return $this->belongsToMany(Cours::class, 'avancement_cours', 'id_user', 'id_cours')
            ->withTimestamps();
    }
}