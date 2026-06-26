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
    ];

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
        return $this->hasMany(Rendu::class, 'id_user');
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