<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends Model
{
    // On spécifie le nom exact de la table au singulier
    protected $table = 'chat';

    protected $fillable = ['id_user'];

    /**
     * Le chat appartient à un utilisateur (l'étudiant).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Un chat contient plusieurs messages.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'id_chat');
    }

    /**
     * Dernier message du chat (une seule requête pour toute une liste).
     */
    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class, 'id_chat')->latestOfMany();
    }
}
