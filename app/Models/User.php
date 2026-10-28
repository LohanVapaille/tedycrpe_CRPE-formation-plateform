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
     * Toutes les valeurs possibles du champ "access" (libellé affiché dans l'admin).
     */
    public const ACCES = [
        'all' => 'Tous les cours',
        'CRPE BAC + 5' => 'CRPE BAC +5',
        'CRPE BAC + 5 E-learning' => 'CRPE BAC +5 E-learning',
        'CRPE BAC + 3' => 'CRPE BAC +3',
        'CRPE BAC + 3 E-learning' => 'CRPE BAC +3 E-learning',
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